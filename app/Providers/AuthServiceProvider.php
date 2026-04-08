<?php

namespace App\Providers;

use App\Models\ExtensionProgram;
use App\Models\Community;
use App\Policies\ProgramPolicy;
use App\Policies\CommunityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ExtensionProgram::class => ProgramPolicy::class,
        Community::class => CommunityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Automatically discover policies in the Policies directory
        $this->discoverPolicies();
    }
}
