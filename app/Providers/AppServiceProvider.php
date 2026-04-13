<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\NeedsAssessment;
use App\Observers\NeedsAssessmentObserver;
use App\Services\GoogleDocumentAIService;
use App\Services\AssessmentFieldMapper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Registers Google Document AI as the primary form extraction service
     */
    public function register(): void
    {
        // Register Google Document AI service (primary extractor)
        $this->app->singleton(GoogleDocumentAIService::class, function ($app) {
            try {
                return new GoogleDocumentAIService();
            } catch (\Exception $e) {
                \Log::warning('Failed to initialize Google Document AI', [
                    'error' => $e->getMessage(),
                ]);
                return null;
            }
        });

        // Register Assessment Field Mapper (uses Document AI only)
        $this->app->singleton(AssessmentFieldMapper::class, function ($app) {
            $docAiService = $app->make(GoogleDocumentAIService::class);
            return new AssessmentFieldMapper($docAiService);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        NeedsAssessment::observe(NeedsAssessmentObserver::class);
    }
}
