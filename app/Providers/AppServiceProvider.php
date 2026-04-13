<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\NeedsAssessment;
use App\Observers\NeedsAssessmentObserver;
use App\Services\HuggingFaceMistralService;
use App\Services\LLMFormExtractor;
use App\Services\AssessmentFieldMapper;
use App\Services\GoogleDocumentAIService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Google Document AI service
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

        // Register Assessment Field Mapper with conditional extractors
        $this->app->singleton(AssessmentFieldMapper::class, function ($app) {
            $docAiService = config('app.use_document_ai') ? $app->make(GoogleDocumentAIService::class) : null;
            $llmExtractor = config('app.use_llm_extraction') 
                ? $app->make(LLMFormExtractor::class)
                : null;
            
            return new AssessmentFieldMapper($llmExtractor, $docAiService);
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
