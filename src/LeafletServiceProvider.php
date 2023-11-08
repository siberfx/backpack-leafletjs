<?php

namespace Siberfx\Leafletjs;

use Illuminate\Support\ServiceProvider;

class LeafletServiceProvider extends ServiceProvider
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
    }

    private function publish()
    {
        $crud_views = [
            __DIR__ . '/resources/views' => resource_path('views/vendor/backpack/crud/fields'),
        ];
        $view_component = [
            __DIR__ . '/resources/components' => resource_path('views/components'),
            __DIR__ . '/View' => app_path('View/Components'),
        ];


        $crud_config = [
            __DIR__ . '/config' => config_path('backpack'),
        ];

        $this->publishes([__DIR__ . '/database/migrations/' => database_path('migrations')], 'migrations');
        $this->publishes($view_component, 'component');
        $this->publishes($crud_config, 'config');
        $this->publishes($crud_views, 'views');
        $this->publishes(array_merge($crud_config, $crud_views, $view_component), 'all');

    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
