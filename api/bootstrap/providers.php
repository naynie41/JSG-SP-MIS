<?php

use App\Domain\Access\AccessServiceProvider;
use App\Domain\Benefit\BenefitServiceProvider;
use App\Domain\Graduation\GraduationServiceProvider;
use App\Domain\Grievance\GrievanceServiceProvider;
use App\Domain\Matching\MatchingServiceProvider;
use App\Domain\Notification\NotificationServiceProvider;
use App\Domain\Ops\OpsServiceProvider;
use App\Domain\Programme\ProgrammeServiceProvider;
use App\Domain\Referral\ReferralServiceProvider;
use App\Domain\Registry\RegistryServiceProvider;
use App\Domain\Reporting\ReportingServiceProvider;
use App\Domain\Sync\SyncServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    AccessServiceProvider::class,
    RegistryServiceProvider::class,
    MatchingServiceProvider::class,
    ProgrammeServiceProvider::class,
    BenefitServiceProvider::class,
    NotificationServiceProvider::class,
    ReferralServiceProvider::class,
    GrievanceServiceProvider::class,
    ReportingServiceProvider::class,
    SyncServiceProvider::class,
    GraduationServiceProvider::class,
    OpsServiceProvider::class,
];
