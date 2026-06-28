<?php

namespace App\Providers;

use App\Models\HealthRecord;
use App\Models\Patient;
use App\Policies\HealthRecordPolicy;
use App\Policies\PatientPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        RateLimiter::for('login', function (Request $request): Limit {
            return Limit::perMinute(5)->by(strtolower((string) $request->input('email')) . '|' . $request->ip());
        });

        RateLimiter::for('api', function (Request $request): Limit {
            $identifier = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute(10000000000)->by((string) $identifier);
        });

        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(HealthRecord::class, HealthRecordPolicy::class);

        Gate::define('view-audit-logs', fn($user) => $user->hasAnyRole(['admin', 'auditor']));
    }
}
