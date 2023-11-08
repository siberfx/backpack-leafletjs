## <p align="center">Leaflet with search lat,lng storing for Laravel Backpack 5.x</p>

<p align="center">
 <img src="https://github.com/siberfx/siberfx-leafletjs/raw/main/img/preview.png">
</p>

<img alt="Stars" src="https://img.shields.io/github/stars/siberfx/backpack-leafletjs?style=plastic&labelColor=343b41"/> <img alt="Forks" src="https://img.shields.io/github/forks/siberfx/backpack-leafletjs?style=plastic&labelColor=343b41"/>
 [![Latest Version on Packagist](https://img.shields.io/packagist/dt/siberfx/backpack-leafletjs?style=plastic)](https://packagist.org/packages/siberfx/backpack-leafletjs)

## Installation

You can install the package via composer:

```bash
composer require siberfx/backpack-leafletjs
```

## Usage
``` php

// config/leaflet.php file content, you can modify it by your own settings.
return [
    'model_name' => App\Models\Setting::class,

    'table_name' => 'settings',
    'lat_field' => 'lat',
    'lng_field' => 'lng',

    'mapbox' => [
        'access_token' => env('MAPS_MAPBOX_ACCESS_TOKEN', 'xxxxxxxxxxxxxxxxxxxxx'),
    ],
];

```

### Publish files

``` bash
php artisan vendor:publish --provider="Backpack\Leafletjs\LeafLetServiceProvider" --tag="migrations" #publish the migration file
php artisan vendor:publish --provider="Backpack\Leafletjs\LeafLetServiceProvider" --tag="config" #publish the config file
php artisan vendor:publish --provider="Backpack\Leafletjs\LeafLetServiceProvider" --tag="lang" #publish the lang files
php artisan vendor:publish --provider="Backpack\Leafletjs\LeafLetServiceProvider" --tag="view_components" #publish the lang files
```
or
``` bash
php artisan vendor:publish --provider="Backpack\Leafletjs\LeafLetServiceProvider" --tag="all" 
```


### Add Leaflet fields to your model

You can override in which table are located your `"lat, lng"` fields and even the model you want to create the fields with the help of `config/backpack/leaflet.php` file and `table_name` field if its set already

```php
$fillable = [
    'lat',
    'lng',
    ...
];
```
or
```php
config('backpack.leaflet.lat_field'), // or 'lat'
config('backpack.leaflet.lng_field') // or 'lng'

```

### Call it inside your controller like this or

``` php
// Add LeafletFields trait to your Crud Controller
use Backpack\Leafletjs\Http\Controllers\Admin\Traits\LeafletCrud;

// and call if your using App\Model\Settings model as your instance:
$this->setLeafletFields();

// to add default fields
```
or add in your Crud controller manually where you want to see it as shown below.

```php

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


```
or

```php

$this->crud->addField([
        'name' => 'leafletMapId', // this is not a name of field in database.
        'type' => 'leaflet',
        'model' => config('backpack.leaflet.model_name'), // you can modify under config folder or override by your own for each model
        'options' => [
            'provider' => 'mapbox',  // default algolia map provider
            'marker_image' => null   // optional
        ],
        'autogenerate' => true, // if you dont want to create the fields in crud controller for lat and lng you specified, it generates himself.
   '    hint' => '<em>You can also drag and adjust your mark by clicking</em>'
 ]);

```

### Security

If you discover any security related issues, please email info@siberfx.com instead of using the issue tracker.

## Credits

- [Selim Gormus](https://github.com/siberfx)

