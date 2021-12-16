<?php

namespace Siberfx\BackpackLeafletjs\Http\Controllers\Admin\Traits;

use Backpack\CRUD\CrudPanel;

/**
 * Trait LeafletCrud
 * @package Siberfx\Leafletjs\Http\Controllers\Admin\Traits
 *
 * @property CrudPanel crud
 */
trait LeafletCrud
{
    protected function setSeoFields(array $extras = [])
    {
        $model = $this->crud->getModel();

        abort_if(
            empty($model->leafletFields) || !is_array($model->leafletFields),
            500,
            sprintf('Your model %s need to use LeafletFields trait or contain $leafletFields array', get_class($model))
        );


        $this->crud->addField([
            'name' => 'leafjs-mapId',
            'type' => 'leafjs',
            'model' => Numen::class,
            'options' => [
                'provider' => 'mapbox',  // default algolia map provider
                'marker_image' => null   // optional
            ],
            'hint' => '<em>You can also drag and adjust your mark by clicking</em>'
        ]);

        foreach ($model->leafletFields as $fieldName => $fieldAttribute) {
            $this->crud->addField(
                array_merge(
                    [
                        'name' => $fieldName,
                        'type'  => 'text',
                    ],
                    $extras,
                    $fieldAttribute
                )
            );
        }
    }
}
