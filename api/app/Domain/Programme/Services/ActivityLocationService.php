<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\ActivityLocation;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Http\Requests\Programme\Concerns\ValidatesLocationSet;
use Illuminate\Support\Facades\DB;

/**
 * Turns a submitted location set into `activity_locations` rows.
 *
 * The submitted shape is one entry per LGA — `{ lga_id, ward_ids: [...] }`, or
 * `{ lga_id, whole_lga: true }` — because that is how the set is chosen in the UI and
 * how it reads back. It is flattened to one row per ward here, so the storage shape
 * stays queryable by coverage/GIS aggregations.
 *
 * Validation lives in {@see ValidatesLocationSet}
 * (it must produce field-level 422s); this service assumes a validated set and owns
 * only the flatten + replace.
 *
 * DESCRIPTIVE ONLY: nothing here — and nothing anywhere — checks the beneficiaries
 * uploaded under the activity against these places.
 */
class ActivityLocationService
{
    /**
     * Replaces an activity's entire location set.
     *
     * Replace rather than merge: the set is edited as a whole in the UI, so a submitted
     * set is the complete intended statement, and removing an LGA has to remove its
     * wards with it. Wrapped in a transaction so a failure never leaves an activity
     * with half a set.
     *
     * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
     */
    public function sync(Activity $activity, array $set): void
    {
        DB::transaction(function () use ($activity, $set): void {
            $activity->locations()->delete();

            $rows = [];
            foreach ($this->flatten($set) as [$lgaId, $wardId]) {
                $rows[] = ['activity_id' => $activity->id, 'lga_id' => $lgaId, 'ward_id' => $wardId];
            }

            foreach ($rows as $row) {
                ActivityLocation::query()->create($row);
            }
        });

        $activity->unsetRelation('locations');
    }

    /**
     * One (lga_id, ward_id) pair per row; ward_id null for a whole-LGA declaration.
     *
     * An entry with no wards is a whole-LGA row — selecting an LGA and no wards means
     * the same thing as ticking "whole LGA", and storing it as one null-ward row keeps
     * a single representation of that idea instead of two.
     *
     * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
     * @return list<array{0: string, 1: string|null}>
     */
    public function flatten(array $set): array
    {
        $pairs = [];

        foreach ($set as $entry) {
            $lgaId = $entry['lga_id'];
            $wardIds = $entry['ward_ids'] ?? [];

            if (($entry['whole_lga'] ?? false) === true || $wardIds === []) {
                $pairs[$lgaId.'|'] = [$lgaId, null];

                continue;
            }

            foreach ($wardIds as $wardId) {
                $pairs[$lgaId.'|'.$wardId] = [$lgaId, $wardId];
            }
        }

        // Keyed to de-duplicate: the same ward submitted twice is a UI slip, not a
        // reason to fail, and the unique index would reject it anyway.
        return array_values($pairs);
    }

    /**
     * The set as the API returns it: grouped by LGA, wards nested, whole-LGA flagged.
     *
     * Built from the loaded relation so a caller that eager-loaded pays no extra query.
     *
     * @return list<array<string, mixed>>
     */
    public function present(Activity $activity): array
    {
        $locations = $activity->relationLoaded('locations')
            ? $activity->locations
            : $activity->locations()->with(['lga', 'ward'])->get();

        // A caller may have eager-loaded `locations` without their lga/ward — load them
        // once here rather than letting the loop below fire a query per row.
        $locations->loadMissing(['lga', 'ward']);

        $grouped = [];

        foreach ($locations as $location) {
            $lga = $location->lga;
            $key = $location->lga_id;

            $grouped[$key] ??= [
                'lga_id' => $location->lga_id,
                'lga_code' => $lga->code,
                'lga_name' => $lga->name,
                'whole_lga' => false,
                'wards' => [],
            ];

            if ($location->isWholeLga()) {
                $grouped[$key]['whole_lga'] = true;

                continue;
            }

            $grouped[$key]['wards'][] = [
                'ward_id' => $location->ward_id,
                'ward_code' => $location->ward?->code,
                'ward_name' => $location->ward?->name,
            ];
        }

        foreach ($grouped as &$entry) {
            usort($entry['wards'], fn (array $a, array $b): int => strcmp((string) $a['ward_name'], (string) $b['ward_name']));
        }
        unset($entry);

        $ordered = array_values($grouped);
        usort($ordered, fn (array $a, array $b): int => strcmp((string) $a['lga_name'], (string) $b['lga_name']));

        return $ordered;
    }

    /**
     * Wards that do not belong to the LGA they were submitted under, keyed by ward id.
     *
     * Shared by the form requests so the "ward belongs to its LGA" rule has exactly one
     * implementation. Returns the offenders rather than a bool so the caller can name
     * them in the error.
     *
     * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
     * @return array<string, string> ward id => the lga id it was wrongly submitted under
     */
    public function misplacedWards(array $set): array
    {
        $wanted = [];
        foreach ($set as $entry) {
            foreach ($entry['ward_ids'] ?? [] as $wardId) {
                $wanted[$wardId] = $entry['lga_id'];
            }
        }

        if ($wanted === []) {
            return [];
        }

        $actual = Ward::query()->whereIn('id', array_keys($wanted))->pluck('lga_id', 'id');

        $misplaced = [];
        foreach ($wanted as $wardId => $lgaId) {
            // A ward that does not exist is reported by the `exists` rule, not here.
            if ($actual->has($wardId) && $actual[$wardId] !== $lgaId) {
                $misplaced[$wardId] = $lgaId;
            }
        }

        return $misplaced;
    }

    /**
     * @param  list<array{lga_id: string, ward_ids?: list<string>, whole_lga?: bool}>  $set
     * @return list<string> lga ids submitted more than once
     */
    public function duplicateLgas(array $set): array
    {
        $counts = array_count_values(array_map(fn (array $e): string => $e['lga_id'], $set));

        return array_keys(array_filter($counts, fn (int $n): bool => $n > 1));
    }

    /**
     * @return array<string, string> lga id => name, for error messages
     */
    public function lgaNames(array $ids): array
    {
        return Lga::query()->whereIn('id', $ids)->pluck('name', 'id')->all();
    }
}
