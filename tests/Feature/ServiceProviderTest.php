<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use Backpack\CRUD\ViewNamespaces;
use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\Test;
use Siberfx\Leafletjs\LeafletServiceProvider;
use Siberfx\Leafletjs\Tests\TestCase;
use Siberfx\Leafletjs\View\Components\LeafletFrontend;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function it_merges_the_package_config_under_the_backpack_namespace(): void
    {
        $this->assertSame('settings', config('backpack.leaflet.table_name'));
        $this->assertSame('lat', config('backpack.leaflet.lat_field'));
        $this->assertSame('lng', config('backpack.leaflet.lng_field'));
    }

    #[Test]
    public function it_registers_the_field_view_namespace_with_backpack(): void
    {
        $this->assertContains('leafletjs::fields', ViewNamespaces::getFor('fields'));
        $this->assertTrue(view()->exists('leafletjs::fields.leaflet'));
    }

    #[Test]
    public function it_registers_the_blade_component(): void
    {
        $this->assertSame(LeafletFrontend::class, Blade::getClassComponentAliases()['leaflet-frontend']);
    }

    #[Test]
    public function it_registers_publishable_groups(): void
    {
        foreach (['leafletjs-config', 'leafletjs-views', 'leafletjs-migrations'] as $tag) {
            $this->assertNotEmpty(LeafletServiceProvider::pathsToPublish(LeafletServiceProvider::class, $tag), $tag);
        }
    }
}
