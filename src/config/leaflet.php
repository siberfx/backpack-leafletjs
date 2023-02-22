<?php

return [
    'table_name' => 'settings',
    'model_name' => App\Models\Setting::class,

    'lat_field' => 'lat',
    'lng_field' => 'lng',

    'mapbox' => [
        'access_token' => env('MAPS_MAPBOX_ACCESS_TOKEN', 'pk.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
    ],
];
