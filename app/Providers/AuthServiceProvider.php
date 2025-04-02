<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define review deletion gate
        Gate::define('delete-review', function ($user, $review) {
            // Admin or review owner can delete
            return $user->role === 'admin' || $user->id === $review->user_id;
        });

        // Alternative policy-style definition (recommended if you have many review-related gates)
        Gate::define('update-review', function ($user, $review) {
            return $user->id === $review->user_id;
        });

        // If you're using roles/permissions, consider:
        Gate::before(function ($user) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}