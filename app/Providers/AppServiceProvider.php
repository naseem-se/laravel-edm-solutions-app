<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // URL::forceScheme('https');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Activity::saving(function (Activity $activity) {
            if (request()->ip()) {
                $activity->properties = $activity->properties->merge([
                    'ip' => request()->ip(),
                ]);
            }

            if (Auth::check()) {
                $activity->causer_id = Auth::id();
                $activity->causer_type = Auth::user()::class;
            }
        });
    }
}
