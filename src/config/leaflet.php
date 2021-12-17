<?php

return [
    'model_name' => App\Models\Setting::class,

    'table_name' => 'settings',
    'lat_field' => 'lat',
    'lng_field' => 'lng',

    'mapbox' => [
        'access_token' => env('MAPS_MAPBOX_ACCESS_TOKEN', 'pk.eyJ1Ijoic2liZXJmeCIsImEiOiJja3NrM2VsN3YwYXN4MndudXVzemd6djh2In0.igGF8rao8oH-aYvtGgxb9A'),
    ],
];
