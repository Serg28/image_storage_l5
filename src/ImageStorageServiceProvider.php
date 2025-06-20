<?php namespace Linecore\ImageStorage;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ImageStorageServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    
    public function boot()
    {
        // Remove vendor autoload requirement as it's handled by Composer
        $this->setupRoutes($this->app->router);
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'image-storage');

        $this->publishes([
            __DIR__ . '/published' => public_path('packages/linecore/image-storage'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/published' => public_path('packages/linecore/image-storage'),
        ], 'image-storage-public');

        $this->publishes([
            __DIR__ . '/config' => config_path('image-storage/')
        ], 'image-storage-config');

        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/Routers/routers.php';
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function provides()
    {
    }
}



