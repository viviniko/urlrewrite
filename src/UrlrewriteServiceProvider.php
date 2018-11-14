<?php

namespace Viviniko\Urlrewrite;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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

        Route::macro('rewrite', function ($entityType, $targetRoute) {
            Rewrite::rewrite($entityType, $targetRoute);
        });

        Request::macro('rewrite', function (Request $request = null) {
            static $rewrite;
            if ($request) {
                $rewrite = $request;
            }

            return $rewrite;
        });

        Paginator::currentPathResolver(function () {
            $request = $this->app['request'];

            return ($request->rewrite() ?? $request)->url();
        });
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
        $this->app->singleton('urlrewrite', \Viviniko\Urlrewrite\Services\UrlrewriteServiceImpl::class);
        $this->app->alias('urlrewrite', \Viviniko\Urlrewrite\Services\UrlrewriteService::class);
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
        ];
    }
}