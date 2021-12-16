## <p align="center">Leafletjs Implementation for Laravel Backpack ^4.1</p>

<p align="center">
 <img src="https://github.com/siberfx/backpack-leafletjs/raw/main/img/preview.png">
</p>

<img alt="Stars" src="https://img.shields.io/github/stars/siberfx/backpack-leafjs?style=plastic&labelColor=343b41"/> <img alt="Forks" src="https://img.shields.io/github/forks/siberfx/backpack-leafjs?style=plastic&labelColor=343b41"/>
 [![Latest Version on Packagist](https://img.shields.io/packagist/dt/siberfx/backpack-leafletjs?style=plastic)](https://packagist.org/packages/siberfx/backpack-leafletjs)

## Installation

You can install the package via composer:

```bash
composer require siberfx/backpack-leafletjs
```

## Usage

``` php
// Add LeafletFields trait to your Backpack Crud Controller
use LeafletFields;

// and call if your using App\Model\Settings model as your instance:
$this->setLeafletFields();

// to add default fields
```

``` php
// Add LeafletFields to your model
use LeafletFields;
```
or  add in your Crud controller manually where you want to see it as shown below. 

``` php
 $this->crud->addField([
                'name' => 'leafjs-mapId', // this is not a name of field in database.
                'type' => 'leafjs',
                'model' => config('leafjs.model_name'), // you can modify under config folder or override by your own for each model
                'options' => [
                    'provider' => 'mapbox',  // default algolia map provider
                    'marker_image' => null   // optional
                ],
                'hint' => '<em>You can also drag and adjust your mark by clicking</em>'
        ]);

```


You can override in which table are located your "lat, lng" fields via config/leafjs.php file and "table_name" field if its set already

To create database you can use migration : 
`php artisan vendor:publish --provider="Siberfx\Leafletjs\LeafletBackpackServiceProvider"`

Add fields in `$fillable` array if you want to save

To set missing fields on your table :
```php
\Siberfx\BackpackLeafletjs\Models\Interfaces\LerafletjsInterface::COLUMN_LONGITUDE;
\Siberfx\BackpackLeafletjs\Models\Interfaces\LerafletjsInterface::COLUMN_LATITUDE;
```

### Security

If you discover any security related issues, please email info@siberfx.com instead of using the issue tracker.

## Credits

- [Selim Gormus](https://github.com/siberfx)

