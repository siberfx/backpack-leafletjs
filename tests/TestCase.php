<?php

namespace Siberfx\Leafletjs\Tests;

use Backpack\Basset\BassetServiceProvider;
use Backpack\Basset\Facades\Basset;
use Backpack\CRUD\BackpackServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Siberfx\Leafletjs\LeafletServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BassetServiceProvider::class,
            BackpackServiceProvider::class,
            LeafletServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return ['Basset' => Basset::class];
    }

    protected function defineEnvironment($app): void
    {
        // Serve assets from their source URLs instead of downloading them during tests.
        $app['config']->set('backpack.basset.dev_mode', true);
    }
}
