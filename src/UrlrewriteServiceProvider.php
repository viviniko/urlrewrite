<?php

namespace Common\Urlrewrite;

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

    /**
     * Register the rewrite service provider.
     *
     * @return void
     */
    protected function registerUrlrewriteService()
    {
        $this->app->singleton(\Viviniko\Urlrewrite\Contracts\UrlrewriteService::class, \Viviniko\Urlrewrite\Services\Urlrewrite\EloquentUrlrewrite::class);

        $this->app->singleton('urlrewrite', \Viviniko\Urlrewrite\Urlrewrite::class);
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
            \Viviniko\Urlrewrite\Contracts\UrlrewriteService::class,
        ];
    }
}