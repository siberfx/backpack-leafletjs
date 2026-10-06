<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Siberfx\Leafletjs\Support\TileLayer;
use Siberfx\Leafletjs\Tests\TestCase;

class TileLayerTest extends TestCase
{
    #[Test]
    public function it_defaults_to_openstreetmap(): void
    {
        $this->assertStringContainsString('tile.openstreetmap.org', TileLayer::resolve()['url']);
    }

    #[Test]
    public function it_falls_back_to_openstreetmap_when_mapbox_has_no_token(): void
    {
        config(['backpack.leaflet.providers.mapbox.options.accessToken' => null]);

        $this->assertStringContainsString('tile.openstreetmap.org', TileLayer::resolve('mapbox')['url']);
    }

    #[Test]
    public function it_uses_mapbox_when_a_token_is_configured(): void
    {
        config(['backpack.leaflet.providers.mapbox.options.accessToken' => 'pk.test']);

        $layer = TileLayer::resolve('mapbox');

        $this->assertStringContainsString('api.mapbox.com', $layer['url']);
        $this->assertSame('pk.test', $layer['options']['accessToken']);
    }
}
