<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Siberfx\Leafletjs\Support\AssetAttributes;
use Siberfx\Leafletjs\Tests\TestCase;

class AssetAttributesTest extends TestCase
{
    #[Test]
    public function it_adds_a_subresource_integrity_hash_to_the_default_cdn_files(): void
    {
        $url = config('backpack.leaflet.assets.leaflet_js');

        $this->assertSame(
            ['integrity' => config('backpack.leaflet.integrity')[$url], 'crossorigin' => 'anonymous'],
            AssetAttributes::for($url),
        );
        $this->assertStringStartsWith('sha384-', AssetAttributes::for($url)['integrity']);
    }

    #[Test]
    public function it_adds_no_hash_for_a_custom_asset_url(): void
    {
        $this->assertSame([], AssetAttributes::for('https://assets.example.test/leaflet.js'));
    }

    #[Test]
    public function it_adds_no_hash_when_the_default_url_is_pinned_to_another_version(): void
    {
        $url = str_replace('leaflet@1.9.4', 'leaflet@1.9.3', config('backpack.leaflet.assets.leaflet_js'));

        $this->assertSame([], AssetAttributes::for($url));
    }
}
