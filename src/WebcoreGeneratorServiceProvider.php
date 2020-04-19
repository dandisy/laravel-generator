<?php

namespace Webcore\Generator;

use Illuminate\Support\ServiceProvider;
use Webcore\Generator\Commands\API\APIControllerGeneratorCommand;
use Webcore\Generator\Commands\API\APIGeneratorCommand;
use Webcore\Generator\Commands\API\APIRequestsGeneratorCommand;
use Webcore\Generator\Commands\API\TestsGeneratorCommand;
use Webcore\Generator\Commands\APIScaffoldGeneratorCommand;
use Webcore\Generator\Commands\Common\MigrationGeneratorCommand;
use Webcore\Generator\Commands\Common\ModelGeneratorCommand;
use Webcore\Generator\Commands\Common\RepositoryGeneratorCommand;
use Webcore\Generator\Commands\Publish\GeneratorPublishCommand;
use Webcore\Generator\Commands\Publish\LayoutPublishCommand;
use Webcore\Generator\Commands\Publish\PublishTemplateCommand;
use Webcore\Generator\Commands\Publish\VueJsLayoutPublishCommand;
use Webcore\Generator\Commands\RollbackGeneratorCommand;
use Webcore\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use Webcore\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use Webcore\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use Webcore\Generator\Commands\Scaffold\ViewsGeneratorCommand;
use Webcore\Generator\Commands\VueJs\VueJsGeneratorCommand;

class WebcoreGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/laravel_generator.php';

        $this->publishes([
            $configPath => config_path('webcore/laravel_generator.php'),
            __DIR__.'/../samples/resources' => base_path('resources'),
            __DIR__.'/../samples/public' => public_path(),
            __DIR__.'/../samples/app/Http/Controllers/AppBaseController.php' => app_path('Http/Controllers/AppBaseController.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('generate.publish', function ($app) {
            return new GeneratorPublishCommand();
        });

        $this->app->singleton('generate.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('generate.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('generate.publish.layout', function ($app) {
            return new LayoutPublishCommand();
        });

        $this->app->singleton('generate.publish.templates', function ($app) {
            return new PublishTemplateCommand();
        });

        $this->app->singleton('generate.api_scaffold', function ($app) {
            return new APIScaffoldGeneratorCommand();
        });

        $this->app->singleton('generate.migration', function ($app) {
            return new MigrationGeneratorCommand();
        });

        $this->app->singleton('generate.model', function ($app) {
            return new ModelGeneratorCommand();
        });

        $this->app->singleton('generate.repository', function ($app) {
            return new RepositoryGeneratorCommand();
        });

        $this->app->singleton('generate.api.controller', function ($app) {
            return new APIControllerGeneratorCommand();
        });

        $this->app->singleton('generate.api.requests', function ($app) {
            return new APIRequestsGeneratorCommand();
        });

        $this->app->singleton('generate.api.tests', function ($app) {
            return new TestsGeneratorCommand();
        });

        $this->app->singleton('generate.scaffold.controller', function ($app) {
            return new ControllerGeneratorCommand();
        });

        $this->app->singleton('generate.scaffold.requests', function ($app) {
            return new RequestsGeneratorCommand();
        });

        $this->app->singleton('generate.scaffold.views', function ($app) {
            return new ViewsGeneratorCommand();
        });

        $this->app->singleton('generate.rollback', function ($app) {
            return new RollbackGeneratorCommand();
        });

        $this->app->singleton('generate.vuejs', function ($app) {
            return new VueJsGeneratorCommand();
        });
        $this->app->singleton('generate.publish.vuejs', function ($app) {
            return new VueJsLayoutPublishCommand();
        });

        $this->commands([
            'generate.publish',
            'generate.api',
            'generate.scaffold',
            'generate.api_scaffold',
            'generate.publish.layout',
            'generate.publish.templates',
            'generate.migration',
            'generate.model',
            'generate.repository',
            'generate.api.controller',
            'generate.api.requests',
            'generate.api.tests',
            'generate.scaffold.controller',
            'generate.scaffold.requests',
            'generate.scaffold.views',
            'generate.rollback',
            'generate.vuejs',
            'generate.publish.vuejs',
        ]);
    }
}
