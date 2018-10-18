<?php

namespace Viviniko\Urlrewrite;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Viviniko\Urlrewrite\Console\Commands\UrlrewriteTableCommand;

class UrlrewriteServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/urlrewrite.php' => config_path('urlrewrite.php'),
        ]);

        // Register commands
        $this->commands('command.urlrewrite.table');

        $urlrewrite = $this->app['urlrewrite'];

        Route::macro('rewrite', function ($entityType, $targetRoute) use ($urlrewrite) {
            $urlrewrite->rewrite($entityType, $targetRoute);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/urlrewrite.php', 'urlrewrite');

        $this->registerRepositories();

        $this->registerUrlrewriteService();

        $this->registerCommands();
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.urlrewrite.table', function ($app) {
            return new UrlrewriteTableCommand($app['files'], $app['composer']);
        });
    }

    protected function registerRepositories()
    {
        $this->app->singleton(
            \Viviniko\Urlrewrite\Repositories\UrlrewriteRepository::class,
            \Viviniko\Urlrewrite\Repositories\EloquentUrlrewrite::class
        );
    }

    /**
     * Register the rewrite service provider.
     *
     * @return void
     */
    protected function registerUrlrewriteService()
    {
        $this->app->singleton(
            \Viviniko\Urlrewrite\Services\UrlrewriteService::class,
            \Viviniko\Urlrewrite\Services\UrlrewriteServiceImpl::class
        );
        $this->app->singleton('urlrewrite', \Viviniko\Urlrewrite\Rewrite::class);
        $this->app->alias('urlrewrite', \Viviniko\Urlrewrite\Contracts\Urlrewrite::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'urlrewrite',
            \Viviniko\Urlrewrite\Services\UrlrewriteService::class,
            \Viviniko\Urlrewrite\Contracts\Urlrewrite::class,
        ];
    }
}