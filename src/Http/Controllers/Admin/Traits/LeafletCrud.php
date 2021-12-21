<?php

namespace Siberfx\Leafletjs\Http\Controllers\Admin\Traits;

use Backpack\CRUD\CrudPanel;

/**
 * Trait LeafletCrud
 * @package Siberfx\Leafletjs\Http\Controllers\Admin\Traits
 *
 * @property CrudPanel crud
 */
trait LeafletCrud
{
    protected function setLeafletFields(array $extras = [])
    {

        $this->crud->addField([
            'name' => 'leafjs-mapId',
            'type' => 'leaflet',
            'model' => config('backpack.leaflet.model_name'),
            'options' => [
                'provider' => 'mapbox',  // default algolia map provider
                'marker_image' => null   // optional
            ],
            'hint' => '<em>You can also drag and adjust your mark by clicking</em>'
        ]);
    }
}
