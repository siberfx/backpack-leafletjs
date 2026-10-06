<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Where the coordinates are stored. Used by the bundled migration and as
    | the default column names for the `leaflet` CRUD field.
    |
    */

    'table_name' => 'settings',

    'lat_field' => 'lat',

    'lng_field' => 'lng',

    /*
    |--------------------------------------------------------------------------
    | Map defaults
    |--------------------------------------------------------------------------
    |
    | The point the map is centred on when no coordinates are stored yet.
    |
    */

    'default_center' => [
        'lat' => 53.8965741,
        'lng' => 27.547158,
    ],

    'default_zoom' => 14,

    /*
    |--------------------------------------------------------------------------
    | Tile provider
    |--------------------------------------------------------------------------
    |
    | "openstreetmap" works out of the box. "mapbox" needs an access token;
    | without one the map falls back to OpenStreetMap tiles.
    |
    */

    'provider' => env('LEAFLET_PROVIDER', env('MAPS_MAPBOX_ACCESS_TOKEN') ? 'mapbox' : 'openstreetmap'),

    'providers' => [

        'openstreetmap' => [
            'url' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            'options' => [
                'maxZoom' => 19,
                'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            ],
        ],

        'mapbox' => [
            'url' => 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
            'options' => [
                'id' => env('MAPS_MAPBOX_STYLE', 'mapbox/streets-v12'),
                'accessToken' => env('MAPS_MAPBOX_ACCESS_TOKEN'),
                'tileSize' => 512,
                'zoomOffset' => -1,
                'maxZoom' => 18,
                'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery &copy; <a href="https://www.mapbox.com/">Mapbox</a>',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    */

    'assets' => [
        'leaflet_js' => 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js',
        'leaflet_css' => 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css',
        'leaflet_images' => 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/',
        'geocoder_js' => 'https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@3.1.0/dist/Control.Geocoder.js',
        'geocoder_css' => 'https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@3.1.0/dist/Control.Geocoder.css',
    ],

];
