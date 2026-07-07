<?php

declare(strict_types=1);

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Referral\Enums\ReferralStatus;

return [
    /*
    |--------------------------------------------------------------------------
    | Referral SLAs (PRD FR-REF-04/05)
    |--------------------------------------------------------------------------
    | `windows` are the DEFAULTS seeded into the admin-editable
    | `referral_sla_policies` table (hours a referral may sit in a status before it
    | is overdue). The DB table is the runtime source of truth; edit it via the API.
    |
    | `escalation_chain` is the ordered list of roles notified at each successive
    | SLA-breach level. It never auto-closes anything — it only escalates + notifies.
    */
    'referral' => [
        'windows' => [
            ReferralStatus::Created->value => 48,             // time to accept
            ReferralStatus::MoreInfoRequested->value => 48,   // time to respond
            ReferralStatus::Accepted->value => 168,           // time to complete (7d)
            ReferralStatus::InProgress->value => 168,
        ],

        'escalation_chain' => [
            RoleKey::MdaAdmin->value,
            RoleKey::SpCoordination->value,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Grievance SLAs (PRD FR-GRM-03)
    |--------------------------------------------------------------------------
    | `windows` are the DEFAULTS seeded into the admin-editable
    | `grievance_sla_policies` table: hours from when a grievance is LOGGED until
    | it must be resolved, PER CATEGORY. The DB table is the runtime source of
    | truth; edit it via the API. An absent category = no SLA for that category.
    |
    | `escalation_chain` is the ordered list of roles an overdue grievance
    | escalates to at each successive breach level — the handling MDA's admins
    | first, then state coordination. It never auto-closes anything.
    */
    'grievance' => [
        'windows' => [
            GrievanceCategory::Payment->value => 72,          // 3d — money not received
            GrievanceCategory::Eligibility->value => 120,     // 5d
            GrievanceCategory::DataCorrection->value => 72,   // 3d
            GrievanceCategory::ServiceQuality->value => 120,  // 5d
            GrievanceCategory::Complaint->value => 120,       // 5d
            GrievanceCategory::Other->value => 168,           // 7d
        ],

        'escalation_chain' => [
            RoleKey::MdaAdmin->value,
            RoleKey::SpCoordination->value,
        ],
    ],
];
