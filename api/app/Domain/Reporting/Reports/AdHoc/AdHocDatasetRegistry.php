<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports\AdHoc;

use App\Domain\Benefit\Models\Benefit;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Support\DashboardScope;

/**
 * The whitelist of ad-hoc datasets (PRD FR-RPT-03). Each dataset exposes ONLY
 * aggregate dimensions (group-by), measures (count/sum), and filters — there is no
 * row-level or identifier column anywhere, so an ad-hoc report is always
 * de-identified and PII can never be selected. Coordination datasets
 * (referrals/grievances) are hidden from a partner's funded-programme scope.
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

    public static function availableTo(string $dataset, DashboardScope $scope): bool
    {
        return isset(self::DATASETS[$dataset])
            && ! (self::isCoordination($dataset) && ! $scope->includesCoordinationMetrics());
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
            if ($dataset['coordination'] && ! $scope->includesCoordinationMetrics()) {
                continue;
            }
            $out[] = [
                'key' => $key,
                'label' => $dataset['label'],
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
