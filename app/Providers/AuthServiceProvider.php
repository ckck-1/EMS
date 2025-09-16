<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Category;
use App\Policies\EventPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate for admin access
        Gate::define('viewAdmin', function ($user) {
            return $user->isAdmin();
        });

        // Gate for organizer access
        Gate::define('viewOrganizer', function ($user) {
            return $user->isOrganizer() || $user->isAdmin();
        });
    }
}