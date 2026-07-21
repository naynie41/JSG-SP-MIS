<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Reporting\Export\SensitiveMasker;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The full "View Activity" picture (PRD §10, DESIGN-SYSTEM §5.10): the activity + its
 * catalog programme, target vs ACTUAL beneficiary counts, the beneficiaries/interventions
 * recorded under it, the import/validation summary of its bound batch(es), and the
 * request-to-serve items attached to the activity with their status.
 *
 * Scoping is enforced upstream (the activity is resolved under {@see MdaScope} + the view
 * policy). Sub-queries inherit the caller's scope; NIN/BVN are masked (SECURITY.md §1/§8),
 * and served-duplicate beneficiaries are shown reveal-only via {@see ServiceRequestResource}
 * (name only — never a foreign MDA's identifiers).
 *
 * @mixin Activity
 */
class ActivityDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Activity $activity */
        $activity = $this->resource;

        $enrollments = Enrollment::query()
            ->where('activity_id', $activity->id)
            ->with('beneficiary')
            ->latest('enrolled_on')
            ->latest('id')
            ->get();

        // The served beneficiary is owned by ANOTHER MDA; the request-to-serve reveal
        // seam (FR-OWN-03) exposes only their name, so load it past the MDA scope —
        // ServiceRequestResource still emits no identifiers.
        $serviceRequests = ServiceRequest::query()
            ->where('activity_id', $activity->id)
            ->with([
                'beneficiary' => fn ($query) => $query->withoutGlobalScope(MdaScope::class),
                'toMda',
            ])
            ->latest('created_at')
            ->get();

        $pending = $serviceRequests->where('status', ServiceRequestStatus::Pending)->count();

        return [
            ...(new ActivityResource($activity))->toArray($request),
            'programme' => $activity->relationLoaded('programme') && $activity->programme !== null
                ? (new ProgrammeResource($activity->programme))->toArray($request)
                : null,
            'counts' => [
                'target' => $activity->target_beneficiaries,
                'actual' => $enrollments->count(),
                'pending_service_requests' => $pending,
            ],
            'beneficiaries' => $enrollments->map(fn (Enrollment $enrollment): array => $this->beneficiaryLine($enrollment))->all(),
            'import_summary' => $this->importSummary($activity),
            'service_requests' => ServiceRequestResource::collection($serviceRequests)->resolve(),
        ];
    }

    /**
     * A beneficiary recorded under the activity, with identifiers masked to last-4.
     *
     * @return array<string, mixed>
     */
    private function beneficiaryLine(Enrollment $enrollment): array
    {
        $beneficiary = $enrollment->beneficiary;

        return [
            'enrollment_id' => $enrollment->id,
            'beneficiary_id' => $enrollment->beneficiary_id,
            'full_name' => $beneficiary?->fullName(),
            'nin' => $this->mask($beneficiary?->nin),
            'bvn' => $this->mask($beneficiary?->bvn),
            'lga' => $beneficiary?->lga,
            'ward' => $beneficiary?->ward,
            'beneficiary_status' => $beneficiary?->status->value,
            'enrollment_status' => $enrollment->status->value,
            'enrolled_on' => $enrollment->enrolled_on->toDateString(),
        ];
    }

    private function mask(?string $value): ?string
    {
        return $value === null ? null : SensitiveMasker::mask($value);
    }

    /**
     * Aggregate the import/validation summary across the activity's bound batch(es).
     *
     * @return array<string, int>|null
     */
    private function importSummary(Activity $activity): ?array
    {
        $batches = ImportBatch::query()->where('activity_id', $activity->id)->get();

        if ($batches->isEmpty()) {
            return null;
        }

        return [
            'batches' => $batches->count(),
            'total_rows' => (int) $batches->sum('total_rows'),
            'valid_rows' => (int) $batches->sum('valid_rows'),
            'invalid_rows' => (int) $batches->sum('invalid_rows'),
            'rejected_rows' => (int) $batches->sum('rejected_rows'),       // malformed identity (FR-REG-05)
            'dropped_field_rows' => (int) $batches->sum('dropped_field_rows'), // non-identity field dropped (FR-REG-09)
            'committed_rows' => (int) $batches->sum('committed_rows'),     // new beneficiaries saved
            'served_rows' => (int) $batches->sum('served_rows'),           // duplicates served via a request
            'skipped_rows' => (int) $batches->sum('skipped_rows'),
        ];
    }
}
