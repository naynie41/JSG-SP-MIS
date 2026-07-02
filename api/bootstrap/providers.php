<?php

use App\Domain\Access\AccessServiceProvider;
use App\Domain\Registry\RegistryServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    AccessServiceProvider::class,
    RegistryServiceProvider::class,
];
