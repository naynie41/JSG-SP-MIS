<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use DomainException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Household membership lifecycle (PRD FR-REG-01, §9 history decision).
 *
 * Memberships are never deleted — a beneficiary's household history is preserved
 * by closing (`left_at`) the current open membership and opening a new one. The
 * single-open-membership rule is enforced here (the partial unique index is the
 * DB backstop); every change is audited at the household level.
 */
class HouseholdMembershipService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /** Add a beneficiary to a household as a new open membership. */
    public function add(Household $household, Beneficiary $beneficiary, HouseholdRole $role): HouseholdMembership
    {
        $this->assertNoOpenMembership($beneficiary);

        $membership = HouseholdMembership::create([
            'household_id' => $household->id,
            'beneficiary_id' => $beneficiary->id,
            'role_in_household' => $role,
        ]);

        $this->audit->record('household.member_added', $household, after: [
            'beneficiary_id' => $beneficiary->id,
            'membership_id' => $membership->id,
            'role_in_household' => $role->value,
        ]);

        return $membership;
    }

    /**
     * Move a beneficiary into a household: close the current open membership
     * (wherever it is) and open a new one — full history retained.
     */
    public function move(Household $toHousehold, Beneficiary $beneficiary, HouseholdRole $role): HouseholdMembership
    {
        return DB::transaction(function () use ($toHousehold, $beneficiary, $role): HouseholdMembership {
            $current = $this->openMembership($beneficiary);
            $fromHouseholdId = $current?->household_id;

            if ($current !== null) {
                if ($current->household_id === $toHousehold->id) {
                    throw new DomainException('The beneficiary is already a member of this household.');
                }
                $current->update(['left_at' => Carbon::now()]);
                $this->clearHeadIfMember($fromHouseholdId, $beneficiary->id);
            }

            $membership = HouseholdMembership::create([
                'household_id' => $toHousehold->id,
                'beneficiary_id' => $beneficiary->id,
                'role_in_household' => $role,
            ]);

            $this->audit->record(
                'household.member_moved',
                $toHousehold,
                before: ['household_id' => $fromHouseholdId],
                after: ['household_id' => $toHousehold->id, 'beneficiary_id' => $beneficiary->id, 'membership_id' => $membership->id],
            );

            return $membership;
        });
    }

    /** Remove a beneficiary from a household by closing their open membership. */
    public function remove(Household $household, Beneficiary $beneficiary): HouseholdMembership
    {
        $membership = HouseholdMembership::query()
            ->where('household_id', $household->id)
            ->where('beneficiary_id', $beneficiary->id)
            ->whereNull('left_at')
            ->first();

        if ($membership === null) {
            throw new DomainException('The beneficiary has no open membership in this household.');
        }

        $membership->update(['left_at' => Carbon::now()]);
        $this->clearHeadIfMember($household->id, $beneficiary->id);

        $this->audit->record('household.member_removed', $household, after: [
            'beneficiary_id' => $beneficiary->id,
            'membership_id' => $membership->id,
        ]);

        return $membership;
    }

    /**
     * Designate/replace the household head. The head must be a current open member;
     * their membership role is set to Head.
     */
    public function designateHead(Household $household, Beneficiary $beneficiary): void
    {
        $membership = HouseholdMembership::query()
            ->where('household_id', $household->id)
            ->where('beneficiary_id', $beneficiary->id)
            ->whereNull('left_at')
            ->first();

        if ($membership === null) {
            throw new DomainException('The head must be a current member of the household.');
        }

        $membership->update(['role_in_household' => HouseholdRole::Head]);
        $household->update(['head_beneficiary_id' => $beneficiary->id]);

        $this->audit->record('household.head_designated', $household, after: [
            'head_beneficiary_id' => $beneficiary->id,
        ]);
    }

    private function openMembership(Beneficiary $beneficiary): ?HouseholdMembership
    {
        return HouseholdMembership::query()
            ->where('beneficiary_id', $beneficiary->id)
            ->whereNull('left_at')
            ->first();
    }

    private function assertNoOpenMembership(Beneficiary $beneficiary): void
    {
        if ($this->openMembership($beneficiary) !== null) {
            throw new DomainException('This beneficiary already belongs to a household. Move them instead of opening a second membership.');
        }
    }

    /** If the departing beneficiary headed their old household, vacate that role. */
    private function clearHeadIfMember(?string $householdId, string $beneficiaryId): void
    {
        if ($householdId === null) {
            return;
        }

        $household = Household::query()->withoutGlobalScope(MdaScope::class)->find($householdId);

        if ($household !== null && $household->head_beneficiary_id === $beneficiaryId) {
            $household->update(['head_beneficiary_id' => null]);
        }
    }
}
