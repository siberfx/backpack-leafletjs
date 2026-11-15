<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Siberfx\Leafletjs\Tests\TestCase;

class LeafletFrontendTest extends TestCase
{
    #[Test]
    public function it_renders_a_map_with_markers(): void
    {
        $html = $this->blade(
            '<x-leaflet-frontend id="office-map" :center-point="[52.37, 4.89]" :markers="$markers" height="250px" />',
            ['markers' => [[52.37, 4.89], ['lat' => 52.1, 'lng' => 5.1, 'popup' => '<b>HQ</b>']]],
        );

        $html->assertSee('id="office-map"', false)
            ->assertSee('height: 250px', false)
            ->assertSee('L.map("office-map")', false)
            ->assertSee('[52.37,4.89]', false)
            ->assertSee('"lat":52.1,"lng":5.1', false)
            ->assertDontSee('<b>HQ</b>', false);
    }

    #[Test]
    public function it_generates_a_map_id_and_uses_the_default_center(): void
    {
        $this->blade('<x-leaflet-frontend />')
            ->assertSee('id="leaflet-', false)
            ->assertSee('[53.8965741,27.547158]', false);
    }

    #[Test]
    public function it_zooms_to_the_configured_default_zoom(): void
    {
        $this->blade('<x-leaflet-frontend />')
            ->assertSee('], 14);', false);

        config(['backpack.leaflet.default_zoom' => 9]);

        $this->blade('<x-leaflet-frontend />')
            ->assertSee('], 9);', false);
    }

    #[Test]
    public function it_accepts_an_explicit_zoom_level(): void
    {
        $this->blade('<x-leaflet-frontend :zoom-level="5" />')
            ->assertSee('], 5);', false);
    }

    #[Test]
    public function it_selects_the_tile_provider_with_the_provider_attribute(): void
    {
        config(['backpack.leaflet.providers.mapbox.options.accessToken' => 'pk.test']);

        $this->blade('<x-leaflet-frontend provider="mapbox" />')
            ->assertSee('api.mapbox.com', false);
    }

    #[Test]
    public function it_still_accepts_tile_host_as_an_alias_of_provider(): void
    {
        config(['backpack.leaflet.providers.mapbox.options.accessToken' => 'pk.test']);

        $this->blade('<x-leaflet-frontend tile-host="mapbox" />')
            ->assertSee('api.mapbox.com', false);
    }
}
