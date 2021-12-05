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
            sprintf('Your model %s need to use LeafletFields trait or contain $leafletFields array', get_class($model)));


        $this->crud->addField([
            'name'  => 'map_leafletjs',
            'type'  => 'custom_view',
            'value' => '<br><h2>MAP</h2><hr>'
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
