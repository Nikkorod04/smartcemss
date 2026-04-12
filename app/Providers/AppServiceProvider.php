<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\NeedsAssessment;
use App\Observers\NeedsAssessmentObserver;

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
        NeedsAssessment::observe(NeedsAssessmentObserver::class);
    }
}
