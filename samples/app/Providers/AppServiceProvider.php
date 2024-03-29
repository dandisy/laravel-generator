<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if(env('APP_ENV') == 'production')
        // $this->app['request']->server->set('HTTPS', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191); // added by dandisy

        // if(env('APP_ENV') == 'production')
        // URL::forceScheme('https');

        // // added by dandisy
        // View::share('appName', isset(Setting::where('key', "app-name")->first()->value) ? Setting::where('key', "app-name")->first()->value : 'Webcore');
        // View::addNamespace('microsite', storage_path('app/public/microsite'));
    }
}
