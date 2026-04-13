<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\NeedsAssessment;
use App\Observers\NeedsAssessmentObserver;
use App\Services\HuggingFaceMistralService;
use App\Services\LLMFormExtractor;
use App\Services\AssessmentFieldMapper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Hugging Face Mistral service
        $this->app->singleton(HuggingFaceMistralService::class, function ($app) {
            return new HuggingFaceMistralService();
        });

        // Register LLM Form Extractor
        $this->app->singleton(LLMFormExtractor::class, function ($app) {
            return new LLMFormExtractor(
                $app->make(HuggingFaceMistralService::class)
            );
        });

        // Register Assessment Field Mapper with LLM Extractor
        $this->app->singleton(AssessmentFieldMapper::class, function ($app) {
            $llmExtractor = config('app.use_llm_extraction') 
                ? $app->make(LLMFormExtractor::class)
                : null;
            
            return new AssessmentFieldMapper($llmExtractor);
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
