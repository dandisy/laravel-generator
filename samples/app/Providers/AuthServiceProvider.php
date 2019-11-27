<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy', // uncommented by dandisy
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Passport::routes(); // added by dandisy

        // Passport::enableImplicitGrant(); // added by dandisy

        // Passport::tokensExpireIn(Carbon::now()->addDays(15)); // added by dandisy

        // Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); // added by dandisy
    }
}
