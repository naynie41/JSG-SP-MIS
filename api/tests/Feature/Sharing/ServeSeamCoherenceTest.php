<?php

declare(strict_types=1);

namespace Tests\Feature\Sharing;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Services\DeliveryAuthorization;
use App\Domain\Programme\Services\EnrollmentService;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Registry\Services\ServiceRequestService;
use App\Domain\Sharing\DataSharingGuard;
use App\Domain\Sharing\SharingBasis;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The three serve gates must agree (PRD v1.2 FR-OWN-06/07, SECURITY.md §4).
 *
 * "May this MDA act on this beneficiary?" is currently answered in three places, by
 * three different mechanisms:
 *
 *   - {@see DataSharingGuard::basisFor()}        — may I READ the record
 *   - {@see EnrollmentService::canServe()}       — may I ENROLL them
 *   - {@see DeliveryAuthorization::basisFor()}   — may I RECORD A BENEFIT
 *
 * Phase 7 consolidated the READ side behind the guard but left the two write-side gates
 * on their own mechanisms, so nothing structurally stops them drifting apart. Drift here
 * is a privacy incident in one direction (serving someone whose owner never agreed) and
 * a service failure in the other (an accepted request that still refuses delivery).
 *
 * This pins the property rather than any one implementation: for the same
 * (MDA, beneficiary) pair, all three must reach the same allow/deny answer.
 */
class ServeSeamCoherenceTest extends TestCase
{
    use RefreshDatabase;

    private Mda $owner;

    private Mda $other;

    private User $ownerUser;

    private User $otherUser;

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->owner = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->other = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->ownerUser = $this->user($this->owner);
        $this->otherUser = $this->user($this->other);

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);
    }

    private function user(Mda $mda): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    /**
     * The three gates' verdicts for one user, as booleans.
     *
     * @return array{read: bool, enroll: bool, deliver: bool}
     */
    private function verdicts(User $user): array
    {
        $beneficiary = $this->beneficiary->fresh();

        return [
            'read' => app(DataSharingGuard::class)->basisFor($user, $beneficiary) !== SharingBasis::None,
            'enroll' => app(EnrollmentService::class)->canServe($user, $beneficiary),
            'deliver' => app(DeliveryAuthorization::class)->basisFor((string) $user->mda_id, $beneficiary) !== null,
        ];
    }

    private function assertAllAgree(User $user, bool $expected, string $situation): void
    {
        $verdicts = $this->verdicts($user);

        $this->assertSame(
            ['read' => $expected, 'enroll' => $expected, 'deliver' => $expected],
            $verdicts,
            "the three serve gates disagree {$situation}: ".json_encode($verdicts),
        );
    }

    /** Raise and accept a request-to-serve, the way the owner MDA actually would. */
    private function acceptedRequest(): ServiceRequest
    {
        $request = ServiceRequest::create([
            'beneficiary_id' => $this->beneficiary->id,
            'from_mda_id' => $this->other->id,
            'to_mda_id' => $this->owner->id,
            'status' => ServiceRequestStatus::Pending,
            'reason' => 'Enrolling her in a feeding activity',
        ]);

        app(ServiceRequestService::class)->accept($request, $this->ownerUser);

        return $request->fresh();
    }

    /* ------------------------------------------------------------------ agreement */

    public function test_all_three_gates_admit_the_owner(): void
    {
        $this->assertAllAgree($this->ownerUser, true, 'for the owner MDA');
    }

    public function test_all_three_gates_refuse_a_stranger(): void
    {
        $this->assertAllAgree($this->otherUser, false, 'for a non-owner with no grant');
    }

    public function test_all_three_gates_admit_a_non_owner_after_acceptance(): void
    {
        $this->acceptedRequest();

        // FR-OWN-07: acceptance opens read AND authorizes serving. A gate that still
        // refused here would make an accepted request meaningless.
        $this->assertAllAgree($this->otherUser, true, 'after the owner accepted a service request');
    }

    public function test_all_three_gates_refuse_again_once_the_grant_is_revoked(): void
    {
        $this->acceptedRequest();
        $this->assertAllAgree($this->otherUser, true, 'while the grant is open');

        BeneficiaryServiceGrant::query()->withoutGlobalScopes()
            ->where('beneficiary_id', $this->beneficiary->id)
            ->where('mda_id', $this->other->id)
            ->update(['revoked_at' => now()]);

        // Revocation has to close every door, not just the one that was checked.
        $this->assertAllAgree($this->otherUser, false, 'after the grant was revoked');
    }

    public function test_a_pending_request_grants_nothing_anywhere(): void
    {
        ServiceRequest::create([
            'beneficiary_id' => $this->beneficiary->id,
            'from_mda_id' => $this->other->id,
            'to_mda_id' => $this->owner->id,
            'status' => ServiceRequestStatus::Pending,
            'reason' => 'Awaiting a decision',
        ]);

        // The owner's decision is the thing that grants access — asking is not enough.
        $this->assertAllAgree($this->otherUser, false, 'while the request is only pending');
    }

    public function test_a_declined_request_grants_nothing_anywhere(): void
    {
        $request = ServiceRequest::create([
            'beneficiary_id' => $this->beneficiary->id,
            'from_mda_id' => $this->other->id,
            'to_mda_id' => $this->owner->id,
            'status' => ServiceRequestStatus::Pending,
            'reason' => 'Requesting to serve',
        ]);

        app(ServiceRequestService::class)->decline($request, $this->ownerUser, 'Already served this quarter');

        $this->assertAllAgree($this->otherUser, false, 'after the owner declined');
    }

    /* ------------------------------------------- the grant is per-beneficiary only */

    public function test_a_grant_does_not_leak_to_the_owners_other_beneficiaries(): void
    {
        $this->acceptedRequest();

        // Least privilege (SECURITY.md §4): the grant is scoped to the beneficiary
        // served, never to the owner MDA's registry at large.
        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->owner->id]);

        $this->assertAllAgree($this->otherUser, false, 'for a different beneficiary of the same owner');
    }
}
