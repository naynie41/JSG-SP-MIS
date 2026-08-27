<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports\AdHoc;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Reporting\Support\DashboardScope;

/**
 * The whitelist of ad-hoc datasets (PRD FR-RPT-03). Each dataset exposes ONLY
 * aggregate dimensions (group-by), measures (count/sum), and filters — there is no
 * row-level or identifier column anywhere, so an ad-hoc report is always
 * de-identified and PII can never be selected. Coordination datasets
 * (referrals/grievances) are hidden from a partner's funded-programme scope.
 *
 * Datasets flagged `admin` are the ADMINISTRATIVE/governance datasets behind the
 * System Administrator console's Reports section — users, organizations, the
 * programme catalogue, duplicate review, the audit log and import batches. They are
 * available only to a scope carrying `governance` (a System Administrator), never to
 * state-wide oversight: an Executive sees all programme data but not who did what.
 * Their dimensions are counts over administrative attributes only — a user report can
 * group by role or status but never by name or email, and an audit report never
 * exposes the before/after payload.
 *
 * This registry is the single source of truth: the builder validates every
 * definition against it, and the API catalogue is derived from it.
 */
final class AdHocDatasetRegistry
{
    /**
     * @var array<string, array<string, mixed>>
     */
    public const DATASETS = [
        'benefits' => [
            'label' => 'Benefits (ledger)',
            'coordination' => false,
            'model' => Benefit::class,
            'exclude_reversed' => true,
            'dimensions' => [
                'programme' => ['label' => 'Programme', 'column' => 'programme_id', 'render' => 'programme'],
                'mda' => ['label' => 'Delivering MDA', 'column' => 'mda_id', 'render' => 'mda'],
                'lga' => ['label' => 'LGA', 'column' => 'lga', 'render' => 'title'],
                'ward' => ['label' => 'Ward', 'column' => 'ward', 'render' => 'title'],
                'benefit_type' => ['label' => 'Benefit type', 'column' => 'benefit_type', 'render' => 'title'],
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
            ],
            'measures' => [
                'count' => ['label' => 'Deliveries', 'sql' => 'count(*)', 'render' => 'int'],
                'total_value' => ['label' => 'Value (₦)', 'sql' => 'coalesce(sum(monetary_value), 0)', 'render' => 'naira'],
                'total_quantity' => ['label' => 'Quantity', 'sql' => 'coalesce(sum(quantity), 0)', 'render' => 'string'],
            ],
            'filters' => [
                'programme_id' => ['column' => 'programme_id', 'kind' => 'equals'],
                'mda_id' => ['column' => 'mda_id', 'kind' => 'equals'],
                'lga' => ['column' => 'lga', 'kind' => 'equals'],
                'ward' => ['column' => 'ward', 'kind' => 'equals'],
                'benefit_type' => ['column' => 'benefit_type', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'date_from' => ['column' => 'delivery_date', 'kind' => 'date_from'],
                'date_to' => ['column' => 'delivery_date', 'kind' => 'date_to'],
            ],
        ],
        'beneficiaries' => [
            'label' => 'Beneficiaries (registry)',
            'coordination' => false,
            'model' => Beneficiary::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'owner_mda' => ['label' => 'Owner MDA', 'column' => 'owner_mda_id', 'render' => 'mda'],
                'lga' => ['label' => 'LGA', 'column' => 'lga', 'render' => 'title'],
                'ward' => ['label' => 'Ward', 'column' => 'ward', 'render' => 'title'],
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
                'registration_source' => ['label' => 'Source', 'column' => 'registration_source', 'render' => 'title'],
            ],
            'measures' => [
                'count' => ['label' => 'Beneficiaries', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'mda_id' => ['column' => 'owner_mda_id', 'kind' => 'equals'],
                'lga' => ['column' => 'lga', 'kind' => 'equals'],
                'ward' => ['column' => 'ward', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'registration_source' => ['column' => 'registration_source', 'kind' => 'equals'],
                'date_from' => ['column' => 'registration_date', 'kind' => 'date_from'],
                'date_to' => ['column' => 'registration_date', 'kind' => 'date_to'],
            ],
        ],
        /*
         * Activities — an MDA's own delivery vehicles under the shared programme
         * catalogue (§10). Scoped on `owner_mda_id`, the CREATING MDA, so an MDA's
         * activity report is about its own delivery and a programme dimension answers
         * "what are we running, and under which programme" without the catalogue itself
         * ever being reportable to a non-governance scope.
         *
         * `budget_amount` is an activity BUDGET figure, not expenditure — SP-MIS records
         * delivery, it does not move money.
         */
        'activities' => [
            'label' => 'Activities (delivery)',
            'coordination' => false,
            'model' => Activity::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'programme' => ['label' => 'Programme', 'column' => 'programme_id', 'render' => 'programme'],
                'mda' => ['label' => 'Owner MDA', 'column' => 'owner_mda_id', 'render' => 'mda'],
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
                /*
                 * No LGA/ward DIMENSION here, deliberately.
                 *
                 * An activity declares a SET of areas, so grouping by area means joining
                 * `activity_locations` — which repeats an activity once per declared LGA
                 * and makes `count(*)` and `sum(budget_amount)` larger than the truth.
                 * An inflated budget total is exactly the fabricated money figure this
                 * system must never produce, so area stays a FILTER (below), which
                 * narrows without duplicating.
                 */
                'involves_beneficiaries' => ['label' => 'Registers beneficiaries', 'column' => 'involves_beneficiaries', 'render' => 'bool'],
            ],
            'measures' => [
                'count' => ['label' => 'Activities', 'sql' => 'count(*)', 'render' => 'int'],
                'target_beneficiaries' => ['label' => 'Target beneficiaries', 'sql' => 'coalesce(sum(target_beneficiaries), 0)', 'render' => 'int'],
                'budget_amount' => ['label' => 'Budget (₦)', 'sql' => 'coalesce(sum(budget_amount), 0)', 'render' => 'naira'],
            ],
            'filters' => [
                'programme_id' => ['column' => 'programme_id', 'kind' => 'equals'],
                'mda_id' => ['column' => 'owner_mda_id', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                // Matched against the declared location set, not a column: `activities.lga`
                // and `.ward` were dropped when an activity became multi-area.
                'lga' => ['area' => 'lga', 'kind' => 'declared_area'],
                'ward' => ['area' => 'ward', 'kind' => 'declared_area'],
                'date_from' => ['column' => 'starts_on', 'kind' => 'date_from'],
                'date_to' => ['column' => 'ends_on', 'kind' => 'date_to'],
            ],
        ],
        'referrals' => [
            'label' => 'Referrals',
            'coordination' => true,
            'model' => Referral::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
                'from_mda' => ['label' => 'From MDA', 'column' => 'from_mda_id', 'render' => 'mda'],
                'to_mda' => ['label' => 'To MDA', 'column' => 'to_mda_id', 'render' => 'mda'],
            ],
            'measures' => [
                'count' => ['label' => 'Referrals', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'mda_id' => ['column' => null, 'kind' => 'mda_two_party'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],
        'grievances' => [
            'label' => 'Grievances',
            'coordination' => true,
            'model' => Grievance::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
                'category' => ['label' => 'Category', 'column' => 'category', 'render' => 'title'],
                'channel' => ['label' => 'Channel', 'column' => 'channel', 'render' => 'title'],
                'handling_mda' => ['label' => 'Handling MDA', 'column' => 'handling_mda_id', 'render' => 'mda'],
            ],
            'measures' => [
                'count' => ['label' => 'Grievances', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'mda_id' => ['column' => 'handling_mda_id', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'category' => ['column' => 'category', 'kind' => 'equals'],
                'channel' => ['column' => 'channel', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],

        /* ------------------------------------------------------------------------
         | Administrative (governance) datasets — System Administrator console only.
         | Counts over administrative attributes; never a name, email, NIN or an
         | audit before/after payload.
         */

        'users' => [
            'label' => 'Users & access',
            'coordination' => false,
            'admin' => true,
            'model' => User::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'role' => ['label' => 'Role', 'column' => 'role_id', 'render' => 'role'],
                'mda' => ['label' => 'MDA', 'column' => 'mda_id', 'render' => 'mda'],
                'status' => ['label' => 'Account status', 'column' => 'status', 'render' => 'title'],
                'mfa_enabled' => ['label' => 'MFA enrolled', 'column' => 'mfa_enabled', 'render' => 'bool'],
            ],
            'measures' => [
                'count' => ['label' => 'Users', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'mda_id' => ['column' => 'mda_id', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'role_id' => ['column' => 'role_id', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],
        'organizations' => [
            'label' => 'Organizations (MDAs & partners)',
            'coordination' => false,
            'admin' => true,
            'model' => Mda::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'type' => ['label' => 'Type', 'column' => 'type', 'render' => 'title'],
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
            ],
            'measures' => [
                'count' => ['label' => 'Organizations', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'type' => ['column' => 'type', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
            ],
        ],
        'programme_catalogue' => [
            'label' => 'Programme catalogue',
            'coordination' => false,
            'admin' => true,
            'model' => Programme::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'type' => ['label' => 'Type', 'column' => 'type', 'render' => 'title'],
                'benefit_category' => ['label' => 'Benefit category', 'column' => 'benefit_category', 'render' => 'title'],
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
            ],
            'measures' => [
                'count' => ['label' => 'Programmes', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'type' => ['column' => 'type', 'kind' => 'equals'],
                'benefit_category' => ['column' => 'benefit_category', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],
        /*
         * Duplicate review is BOTH governance data (platform-wide data quality) and each
         * MDA's own operational record — the same rows the MDA console's Duplicate
         * Resolution module already shows them. So it stays `admin` (it belongs to the
         * administration console's catalogue and to a governance scope unrestricted) and
         * additionally declares `mda_scopable`: an MDA scope may report on its OWN rows,
         * constrained through the owning import batch in AdHocReportBuilder::applyScope.
         *
         * State-wide-but-not-governance (Executive, SP Coordination) is deliberately NOT
         * admitted by that exception — the invariant that oversight sees programme data
         * but not the platform's own administrative records is unchanged.
         */
        'duplicates' => [
            'label' => 'Duplicate review',
            'coordination' => false,
            'admin' => true,
            'mda_scopable' => true,
            'model' => ImportRow::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'match_band' => ['label' => 'Match band', 'column' => 'match_band', 'render' => 'title'],
                'resolution' => ['label' => 'Resolution', 'column' => 'resolution', 'render' => 'title'],
            ],
            'measures' => [
                'count' => ['label' => 'Rows', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'match_band' => ['column' => 'match_band', 'kind' => 'equals'],
                'resolution' => ['column' => 'resolution', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],
        'audit' => [
            'label' => 'Audit events',
            'coordination' => false,
            'admin' => true,
            'model' => AuditLog::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'action' => ['label' => 'Action', 'column' => 'action', 'render' => 'action'],
                'entity_type' => ['label' => 'Entity', 'column' => 'entity_type', 'render' => 'class'],
                'actor_mda' => ['label' => 'Actor MDA', 'column' => 'actor_mda_id', 'render' => 'mda'],
            ],
            'measures' => [
                'count' => ['label' => 'Events', 'sql' => 'count(*)', 'render' => 'int'],
            ],
            'filters' => [
                'action' => ['column' => 'action', 'kind' => 'equals'],
                'entity_type' => ['column' => 'entity_type', 'kind' => 'equals'],
                'mda_id' => ['column' => 'actor_mda_id', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],
        'imports' => [
            'label' => 'Import batches',
            'coordination' => false,
            'admin' => true,
            'model' => ImportBatch::class,
            'exclude_reversed' => false,
            'dimensions' => [
                'source' => ['label' => 'Source', 'column' => 'source', 'render' => 'title'],
                'status' => ['label' => 'Status', 'column' => 'status', 'render' => 'title'],
                'owner_mda' => ['label' => 'Owner MDA', 'column' => 'owner_mda_id', 'render' => 'mda'],
            ],
            'measures' => [
                'count' => ['label' => 'Batches', 'sql' => 'count(*)', 'render' => 'int'],
                'total_rows' => ['label' => 'Rows', 'sql' => 'coalesce(sum(total_rows), 0)', 'render' => 'int'],
                'valid_rows' => ['label' => 'Valid rows', 'sql' => 'coalesce(sum(valid_rows), 0)', 'render' => 'int'],
                'invalid_rows' => ['label' => 'Invalid rows', 'sql' => 'coalesce(sum(invalid_rows), 0)', 'render' => 'int'],
                'committed_rows' => ['label' => 'Committed rows', 'sql' => 'coalesce(sum(committed_rows), 0)', 'render' => 'int'],
            ],
            'filters' => [
                'mda_id' => ['column' => 'owner_mda_id', 'kind' => 'equals'],
                'source' => ['column' => 'source', 'kind' => 'equals'],
                'status' => ['column' => 'status', 'kind' => 'equals'],
                'date_from' => ['column' => 'created_at', 'kind' => 'date_from'],
                'date_to' => ['column' => 'created_at', 'kind' => 'date_to'],
            ],
        ],
    ];

    /**
     * @return array<string, mixed>|null
     */
    public static function get(string $dataset): ?array
    {
        return self::DATASETS[$dataset] ?? null;
    }

    public static function isCoordination(string $dataset): bool
    {
        return (bool) (self::DATASETS[$dataset]['coordination'] ?? false);
    }

    /** Whether a dataset is administrative/governance data (System Administrator only). */
    public static function isAdmin(string $dataset): bool
    {
        return (bool) (self::DATASETS[$dataset]['admin'] ?? false);
    }

    /**
     * Whether an administrative dataset also admits an MDA scope, restricted to that
     * MDA's own rows. Only `duplicates` does: it is simultaneously platform governance
     * data and the MDA's own operational record. Every such dataset MUST have a
     * corresponding scope clause in AdHocReportBuilder::applyScope — without one the
     * query would be platform-wide.
     */
    public static function isMdaScopable(string $dataset): bool
    {
        return (bool) (self::DATASETS[$dataset]['mda_scopable'] ?? false);
    }

    public static function availableTo(string $dataset, DashboardScope $scope): bool
    {
        if (! isset(self::DATASETS[$dataset])) {
            return false;
        }

        if (self::isCoordination($dataset) && ! $scope->includesCoordinationMetrics()) {
            return false;
        }

        if (self::isAdmin($dataset) && ! $scope->includesGovernanceData()) {
            // The narrow exception: an MDA may report on its own slice. State-wide
            // oversight without governance is still refused.
            return self::isMdaScopable($dataset) && $scope->kind === DashboardScope::KIND_MDA;
        }

        return true;
    }

    /**
     * The public catalogue for a scope: each available dataset with its selectable
     * dimensions, measures and filters (keys + labels only — no SQL).
     *
     * @return list<array<string, mixed>>
     */
    public static function catalogueFor(DashboardScope $scope): array
    {
        $out = [];
        foreach (self::DATASETS as $key => $dataset) {
            if (! self::availableTo($key, $scope)) {
                continue;
            }
            $out[] = [
                'key' => $key,
                'label' => $dataset['label'],
                'admin' => self::isAdmin($key),
                'dimensions' => self::optionList($dataset['dimensions']),
                'measures' => self::optionList($dataset['measures']),
                'filters' => array_keys($dataset['filters']),
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, array<string, mixed>>  $items
     * @return list<array{key: string, label: string}>
     */
    private static function optionList(array $items): array
    {
        $out = [];
        foreach ($items as $key => $item) {
            $out[] = ['key' => $key, 'label' => (string) $item['label']];
        }

        return $out;
    }
}
