<?php

namespace Siberfx\Leafletjs;

use Backpack\CRUD\ViewNamespaces;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Siberfx\Leafletjs\View\Components\LeafletFrontend;

class LeafletServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/leaflet.php', 'backpack.leaflet');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'leafletjs');

        ViewNamespaces::addFor('fields', 'leafletjs::fields');

        Blade::component('leaflet-frontend', LeafletFrontend::class);

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    private function registerPublishing(): void
    {
        $config = [__DIR__.'/../config/leaflet.php' => config_path('backpack/leaflet.php')];
        $views = [__DIR__.'/../resources/views' => resource_path('views/vendor/leafletjs')];
        $migrations = [__DIR__.'/../database/migrations' => database_path('migrations')];

        $this->publishes($config, 'leafletjs-config');
        $this->publishes($views, 'leafletjs-views');
        $this->publishesMigrations($migrations, 'leafletjs-migrations');
    }
}
