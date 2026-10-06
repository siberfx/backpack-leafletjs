<?php

namespace Siberfx\Leafletjs\Http\Controllers\Admin\Traits;

trait LeafletCrud
{
    /**
     * Add a map field that reads and writes the configured lat/lng columns.
     *
     * @param  array<string, mixed>  $extras  Any Backpack field attributes to merge in (label, tab, hint, options...).
     */
    protected function setLeafletFields(array $extras = []): void
    {
        $this->crud->addField(array_replace_recursive([
            'name' => config('backpack.leaflet.lat_field', 'lat').','.config('backpack.leaflet.lng_field', 'lng'),
            'label' => __('Location'),
            'type' => 'leaflet',
            'options' => [
                'provider' => config('backpack.leaflet.provider'),
                'marker_image' => null,
            ],
            'hint' => __('Click the map, drag the marker or search an address to set the location.'),
        ], $extras));
    }
}
