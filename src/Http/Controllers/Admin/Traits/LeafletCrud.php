<?php

namespace Siberfx\Leafletjs\Http\Controllers\Admin\Traits;

/**
 * Trait LeafletCrud
 * @package Siberfx\Leafletjs\Http\Controllers\Admin\Traits
 *
 */
trait LeafletCrud
{
    protected function setLeafletFields(array $extras = [])
    {

        $this->crud->addField([
            'name' => 'leafletMapId', // this is not a name of field in database.
            'type' => 'leaflet',
            'model' => config('backpack.leaflet.model_name'), // you can modify under config folder or override by your own for each model
            'options' => [
                'provider' => 'mapbox',  // default algolia map provider
                'marker_image' => null   // optional
            ],
            'hint' => '<em>You can also drag and adjust your mark by clicking</em>'
        ]);

        $this->crud->addField([
            'name' => 'lat',
            'type' => 'hidden',
            'attributes' => ['id' => 'leafletMapId-lat'],
            'tab' => 'General'
        ]);

        $this->crud->addField([
            'name' => 'lng',
            'type' => 'hidden',
            'attributes' => ['id' => 'leafletMapId-lng'],
            'tab' => 'General'
        ]);
    }
}
