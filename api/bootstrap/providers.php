<?php

use App\Domain\Access\AccessServiceProvider;
use App\Domain\Benefit\BenefitServiceProvider;
use App\Domain\Matching\MatchingServiceProvider;
use App\Domain\Programme\ProgrammeServiceProvider;
use App\Domain\Registry\RegistryServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    AccessServiceProvider::class,
    RegistryServiceProvider::class,
    MatchingServiceProvider::class,
    ProgrammeServiceProvider::class,
    BenefitServiceProvider::class,
];
