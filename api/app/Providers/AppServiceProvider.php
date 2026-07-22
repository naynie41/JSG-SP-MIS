<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Audit\Listeners\AuditEventSubscriber;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiters();

        Event::subscribe(AuditEventSubscriber::class);
    }

    /**
     * Rate limiters for the API. The login limiter throttles by email + IP so
     * brute-forcing one account does not lock out unrelated users (FR-UAM-06,
     * SECURITY.md A07).
     */
    private function configureRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request): Limit {
            $email = Str::lower((string) $request->input('email'));

            return Limit::perMinute(5)->by($email.'|'.$request->ip());
        });

        // MFA challenge attempts, throttled per authenticated user (falling back
        // to IP) to slow brute-forcing of TOTP/recovery codes.
        RateLimiter::for('mfa', function (Request $request): Limit {
            $key = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute(5)->by('mfa|'.$key);
        });

        // Inbound REST registration intake (FR-REG-02), throttled per integrating
        // account so one busy integration cannot starve others.
        RateLimiter::for('registration-intake', function (Request $request): Limit {
            $key = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute(60)->by('intake|'.$key);
        });

        // Bulk PII egress — beneficiary/report exports (SECURITY.md export matrix).
        // A tight per-user ceiling turns a scripted mass-export into noise + audit.
        RateLimiter::for('exports', function (Request $request): Limit {
            $key = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute((int) config('security.rate_limits.exports_per_minute', 10))
                ->by('exports|'.$key);
        });

        // Write-heavy ingestion — bulk imports, offline batches, document uploads.
        RateLimiter::for('imports', function (Request $request): Limit {
            $key = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute((int) config('security.rate_limits.imports_per_minute', 12))
                ->by('imports|'.$key);
        });
    }
}
