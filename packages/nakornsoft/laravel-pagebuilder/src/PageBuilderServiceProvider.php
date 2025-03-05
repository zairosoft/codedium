<?php

namespace Nakornsoft\PageBuilder;


use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PagebuilderServiceProvider extends ServiceProvider
{
    protected $namespace = 'pagebuilder';
    protected $configPath = 'config/config.php';
    protected $publicPath = 'vendor/page-builder';
    protected $routePath = 'routes/web.php';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/'), $this->namespace);
        $this->mergeConfigFrom(__DIR__.'/config/config.php', $this->namespace);


        if ($this->app->runningInConsole()) {
            $this->publishFiles();
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       // $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/resources/views', $this->namespace);
    }

    protected function publishFiles()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path($this->configPath),
        ], [$this->namespace, 'config']);

        $this->publishes([
            __DIR__.'/resources/assets' => public_path($this->publicPath),
        ], [$this->namespace, 'public', 'laravel-assets']);
    }

}
