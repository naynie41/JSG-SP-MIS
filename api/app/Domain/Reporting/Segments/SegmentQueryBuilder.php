<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Turns a {@see SegmentDefinition} into a beneficiary query for a resolved
 * {@see DashboardScope} (FR-RPT-03).
 *
 * The ordering here is the security property, not a style choice: SCOPE IS APPLIED
 * FIRST, then the caller's filters. Every filter can only add a narrowing clause, so
 * no combination of them can reach a row the scope excluded. The builder is not a way
 * around the registry's boundaries; it is a way to ask better questions inside them.
 *
 * The global {@see MdaScope} is deliberately dropped and replaced by the explicit
 * scope. A report runs on the queue with no authenticated user, where the global scope
 * resolves to nothing; the captured DashboardScope is what makes an unattended run
 * apply exactly the boundary the requester had at request time.
 */
class SegmentQueryBuilder
{
    public function __construct(private readonly SegmentDimensionRegistry $registry) {}

    /**
     * @return Builder<Beneficiary>
     */
    public function query(SegmentDefinition $definition, DashboardScope $scope): Builder
    {
        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);

        $this->applyScope($query, $scope);

        foreach ($definition->filters as $key => $filter) {
            $dimension = $this->registry->get($key);
            if ($dimension === null) {
                continue; // unreachable: SegmentDefinition refuses unknown keys
            }

            $this->applyFilter($query, $dimension, $filter['op'], $filter['values']);
        }

        return $query;
    }

    /**
     * The caller's boundary, expressed as query clauses.
     *
     * A PARTNER never reaches this method with row-level intent — the tier check
     * refuses that before a query is built (see {@see SegmentAccess}) — but the scope is
     * still applied, so even an aggregate count is confined to their funded programmes.
     *
     * @param  Builder<Beneficiary>  $query
     */
    private function applyScope(Builder $query, DashboardScope $scope): void
    {
        if ($scope->kind === DashboardScope::KIND_MDA && $scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);

            return;
        }

        if ($scope->kind === DashboardScope::KIND_PARTNER) {
            // A partner's window is the people enrolled in the programmes they fund —
            // never the registry at large. An empty funded list means an empty window,
            // written explicitly so it can never degrade into "no constraint".
            $programmeIds = $scope->programmeIds ?? [];
            $query->whereIn('id', Enrollment::query()
                ->withoutGlobalScope(MdaScope::class)
                ->whereIn('programme_id', $programmeIds)
                ->whereNotNull('beneficiary_id')
                ->select('beneficiary_id'));
        }

        // state_wide: no constraint by design (cross-mda.view).
    }

    /**
     * @param  Builder<Beneficiary>  $query
     * @param  list<string>  $values
     */
    private function applyFilter(Builder $query, SegmentDimension $dimension, string $op, array $values): void
    {
        match (true) {
            $dimension->kind === SegmentDimension::KIND_AGE => $this->applyAge($query, $dimension, $values),
            $dimension->kind === SegmentDimension::KIND_DATE => $query->whereBetween($dimension->column, [
                Carbon::parse($values[0])->startOfDay(),
                Carbon::parse($values[1])->endOfDay(),
            ]),
            $dimension->key === 'household' => $this->applyHousehold($query, $values),
            $dimension->key === 'household_role' => $this->applyHouseholdRole($query, $values),
            $dimension->key === 'programme' => $this->applyEnrollment($query, 'programme_id', $values),
            $dimension->key === 'activity' => $this->applyEnrollment($query, 'activity_id', $values),
            default => $query->whereIn($dimension->column, $values),
        };
    }

    /**
     * Age as DATE-OF-BIRTH BOUNDS, not as a computed age column.
     *
     * `age >= 20 AND age <= 25` would need a per-row date calculation — unindexable, and
     * spelled differently in every database. Inverting it into a birth-date window keeps
     * the comparison on the indexed column and gives the same answer everywhere.
     *
     * The window is inclusive at both ends, which is what "20 to 25" means to the person
     * asking: everyone who has had their 20th birthday, up to the day before their 26th.
     *
     * @param  Builder<Beneficiary>  $query
     * @param  list<string>  $values
     */
    private function applyAge(Builder $query, SegmentDimension $dimension, array $values): void
    {
        $min = (int) $values[0];
        $max = (int) $values[1];

        $today = Carbon::today();
        $earliest = $today->copy()->subYears($max + 1)->addDay(); // turns $max+1 tomorrow at the earliest
        $latest = $today->copy()->subYears($min);                 // had their $min-th birthday by today

        $query->whereNotNull($dimension->column)
            ->whereBetween($dimension->column, [$earliest->toDateString(), $latest->toDateString()]);
    }

    /**
     * @param  Builder<Beneficiary>  $query
     * @param  list<string>  $values
     */
    private function applyEnrollment(Builder $query, string $column, array $values): void
    {
        $query->whereIn('id', Enrollment::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereIn($column, $values)
            ->whereNotNull('beneficiary_id')
            ->select('beneficiary_id'));
    }

    /**
     * Role within the household, from the person's OPEN membership.
     *
     * A closed membership is history: someone who has left is not a household head now,
     * and a report that counted them would overstate every household-targeted programme.
     *
     * @param  Builder<Beneficiary>  $query
     * @param  list<string>  $values
     */
    private function applyHouseholdRole(Builder $query, array $values): void
    {
        $query->whereIn('id', HouseholdMembership::query()
            ->whereNull('left_at')
            ->whereIn('role_in_household', $values)
            ->whereNotNull('beneficiary_id')
            ->select('beneficiary_id'));
    }

    /**
     * Household membership is an OPEN membership, not merely any membership ever held —
     * someone who has left a household is an individual today.
     *
     * @param  Builder<Beneficiary>  $query
     * @param  list<string>  $values
     */
    private function applyHousehold(Builder $query, array $values): void
    {
        $wantsHousehold = in_array('household', $values, true);
        $wantsIndividual = in_array('individual', $values, true);

        if ($wantsHousehold === $wantsIndividual) {
            return; // both or neither selected — no constraint
        }

        $members = HouseholdMembership::query()
            ->whereNull('left_at')
            ->whereNotNull('beneficiary_id')
            ->select('beneficiary_id');

        $wantsHousehold
            ? $query->whereIn('id', $members)
            : $query->whereNotIn('id', $members);
    }
}
