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
}
