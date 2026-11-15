<?php

namespace Siberfx\Leafletjs\Support;

final class AssetAttributes
{
    /**
     * HTML attributes for a Leaflet asset tag. Known CDN files get a Subresource Integrity hash,
     * so a tampered CDN file is refused by the browser. Any other URL (a custom mirror or a
     * different version) gets no integrity attribute, because its hash would not match.
     *
     * @return array<string, string>
     */
    public static function for(string $url): array
    {
        $integrity = config('backpack.leaflet.integrity', [])[$url] ?? null;

        return $integrity
            ? ['integrity' => $integrity, 'crossorigin' => 'anonymous']
            : [];
    }
}
