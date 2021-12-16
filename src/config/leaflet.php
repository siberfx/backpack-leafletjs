<?php

return [
    'table_name' => 'settings',
    'model_name' => App\Models\Setting::class,

    'mapbox' => [
        'access_token' => env('MAPS_MAPBOX_ACCESS_TOKEN', 'pk.eyJ1Ijoic2liZXJmeCIsImEiOiJja3NrM2VsN3YwYXN4MndudXVzemd6djh2In0.igGF8rao8oH-aYvtGgxb9A'),
    ],
];
