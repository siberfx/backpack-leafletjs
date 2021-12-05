<?php

namespace Siberfx\BackpackLeafletjs;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Routing\Router;
use Siberfx\Leafletjs\View\Components\Leafjs;

class ServiceProvider extends IlluminateServiceProvider
{

    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // publish the migrations and seeds

            $this->publish();
        }

        Blade::component('leafjs', Leafjs::class);
    }

    private function publish()
    {
        $crud_views = [
            // Crud Stuff
            __DIR__.'/resources/views' => resource_path('views'),
        ];


        $crud_assets = [
            // Public Stuff
            __DIR__ . '/../../public/css' => public_path('packages/leafletjs/css'),
            __DIR__ . '/../../public/js' => public_path('packages/leafletjs/js'),
        ];

        $crud_config = [
            // Config Stuff
            __DIR__ . '/config' => config_path('backpack/'),
        ];

        $this->publishes([__DIR__ . '/database/migrations/' => database_path('migrations')], 'migrations');
        $this->publishes($crud_assets, 'public');
        $this->publishes($crud_config, 'config');
        $this->publishes($crud_views, 'views');

    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
