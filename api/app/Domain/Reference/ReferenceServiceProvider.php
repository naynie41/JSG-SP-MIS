<?php

declare(strict_types=1);

namespace App\Domain\Reference;

use App\Domain\Reference\Imports\LoadAdministrativeDivisions;
use App\Domain\Reference\Imports\SeedJigawaLgas;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Reference domain: shared, non-PII lookup data (Jigawa LGAs and wards).
 *
 * It registers NO permission. The lookups are read by every authenticated role to
 * render a cascading LGA → Ward selector, so a `reference.view` permission would be
 * granted to every role at once — a permission that can never deny anything makes the
 * RBAC set describe a distinction the system does not draw. The routes are gated by
 * authentication alone, and deliberately so (see routes/api.php).
 */
class ReferenceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([LoadAdministrativeDivisions::class, SeedJigawaLgas::class]);
        }
    }
}
