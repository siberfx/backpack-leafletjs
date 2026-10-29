<h1 align="center">Backpack Leaflet.js</h1>

<p align="center">
    A map field and Blade component for <a href="https://backpackforlaravel.com">Backpack for Laravel</a>. Click, drag or search to store latitude &amp; longitude, powered by <a href="https://leafletjs.com">Leaflet</a>.
</p>

<p align="center">
    <a href="https://packagist.org/packages/siberfx/backpack-leafletjs"><img src="https://img.shields.io/packagist/v/siberfx/backpack-leafletjs?style=flat-square&label=packagist" alt="Latest Version on Packagist"></a>
    <a href="https://packagist.org/packages/siberfx/backpack-leafletjs"><img src="https://img.shields.io/packagist/dt/siberfx/backpack-leafletjs?style=flat-square" alt="Total Downloads"></a>
    <a href="https://github.com/siberfx/backpack-leafletjs/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/siberfx/backpack-leafletjs/tests.yml?branch=main&style=flat-square&label=tests" alt="Tests"></a>
    <a href="https://packagist.org/packages/siberfx/backpack-leafletjs"><img src="https://img.shields.io/packagist/dependency-v/siberfx/backpack-leafletjs/php?style=flat-square" alt="PHP Version"></a>
    <img src="https://img.shields.io/badge/laravel-12.x%20%7C%2013.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12 | 13">
    <img src="https://img.shields.io/badge/backpack-6.8%2B%20%7C%207.x-7C69EF?style=flat-square" alt="Backpack 6.8+ | 7.x">
    <a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/siberfx/backpack-leafletjs?style=flat-square" alt="License"></a>
</p>

<p align="center">
    <img src="https://github.com/siberfx/backpack-leafletjs/raw/main/img/preview.png" alt="Leaflet field preview">
</p>

## Features

- **`leaflet` CRUD field.** Click the map, drag the marker or search an address to set a location.
- **Address search** through [leaflet-control-geocoder](https://github.com/perliedman/leaflet-control-geocoder) (OpenStreetMap Nominatim, no API key).
- **`<x-leaflet-frontend>` Blade component** for maps and markers on public pages.
- **OpenStreetMap by default**, with Mapbox available once you add a token.
- **Nothing to copy.** Views, config and the component are registered by the service provider. Publish only what you want to customise.
- Several maps per form, and maps inside tabs and modals, work out of the box.

## Requirements

| Package              | Supported        |
| -------------------- | ---------------- |
| PHP                  | 8.3 – 8.5        |
| Laravel              | 12.x, 13.x       |
| Backpack for Laravel | 6.8+, 7.x        |

> Need PHP 8.2, Laravel 10/11 or Backpack 5? Use an earlier release of this package (`6.x`).

## Installation

```bash
composer require siberfx/backpack-leafletjs
```

The service provider is auto-discovered.

If you want the package to create the coordinate columns, publish and run the migration. It adds `lat` and `lng` decimal columns to the table set in `backpack.leaflet.table_name`:

```bash
php artisan vendor:publish --tag=leafletjs-migrations
php artisan migrate
```

## Usage

### The CRUD field

Name the field after both coordinate columns, separated by a comma. The field renders and saves both inputs itself:

```php
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

CRUD::field([
    'name' => 'lat,lng',
    'label' => 'Location',
    'type' => 'leaflet',
    'options' => [
        'provider' => 'openstreetmap', // or 'mapbox'. Defaults to config('backpack.leaflet.provider').
        'zoom' => 14,
        'height' => '400px',
        'search' => true,              // show the address search box
        'marker_image' => null,        // optional custom marker icon URL
    ],
    'hint' => 'Click the map, drag the marker or search an address.',
]);
```

Make sure both columns are fillable on your model:

```php
protected $fillable = ['lat', 'lng'];
```

### Using the trait

`LeafletCrud` adds the field using the column names from the config. Extra field settings such as `tab`, `label` or `hint` are merged in:

```php
use Siberfx\Leafletjs\Http\Controllers\Admin\Traits\LeafletCrud;

class PlaceCrudController extends CrudController
{
    use LeafletCrud;

    protected function setupCreateOperation(): void
    {
        $this->setLeafletFields(['tab' => 'General']);
    }
}
```

<details>
<summary>Legacy setup: a map field that writes into your own hidden inputs</summary>

With a single-name field, the map writes into inputs whose ids are `{name}-lat` and `{name}-lng`:

```php
CRUD::field(['name' => 'leafletMapId', 'type' => 'leaflet']);
CRUD::field(['name' => 'lat', 'type' => 'hidden', 'attributes' => ['id' => 'leafletMapId-lat']]);
CRUD::field(['name' => 'lng', 'type' => 'hidden', 'attributes' => ['id' => 'leafletMapId-lng']]);
```

</details>

### Frontend component

Show a map on any Blade page:

```blade
<x-leaflet-frontend
    id="office-map"
    :center-point="[52.3676, 4.9041]"
    :markers="[
        [52.3676, 4.9041],
        ['lat' => 52.0907, 'lng' => 5.1214, 'popup' => 'Utrecht office'],
    ]"
    :zoom-level="9"
    :max-zoom-level="18"
    tile-host="openstreetmap"
    height="450px"
    class="rounded shadow"
/>
```

Every attribute is optional. Without `center-point`, the map centres on `default_center` from the config. Popup text is escaped.

## Configuration

The defaults work without publishing anything. To customise them:

```bash
php artisan vendor:publish --tag=leafletjs-config
```

This creates `config/backpack/leaflet.php`:

```php
return [
    'table_name' => 'settings',   // table used by the migration
    'lat_field' => 'lat',
    'lng_field' => 'lng',

    'default_center' => ['lat' => 53.8965741, 'lng' => 27.547158],
    'default_zoom' => 14,

    'provider' => env('LEAFLET_PROVIDER', env('MAPS_MAPBOX_ACCESS_TOKEN') ? 'mapbox' : 'openstreetmap'),
    'providers' => [/* openstreetmap and mapbox. Add your own tile servers here. */],
    'assets' => [/* Leaflet and geocoder CDN URLs */],
];
```

### Mapbox

Add your token to `.env` and the `mapbox` provider is used automatically:

```dotenv
MAPS_MAPBOX_ACCESS_TOKEN=pk.your-token
MAPS_MAPBOX_STYLE=mapbox/streets-v12
```

If `mapbox` is selected without a token, the map falls back to OpenStreetMap.

### Customising the views

```bash
php artisan vendor:publish --tag=leafletjs-views
```

Published views go to `resources/views/vendor/leafletjs` and take precedence over the package views.

## Testing

```bash
composer install
composer test
```

The suite runs on Orchestra Testbench. CI runs it on PHP 8.3, 8.4 and 8.5 against Laravel 12 and 13.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release notes and the upgrade guide from 6.x.

## Security

If you discover a security issue, please email [info@siberfx.com](mailto:info@siberfx.com) instead of opening a public issue.

## Credits

- [Selim Görmüş](https://github.com/siberfx)
- [All contributors](https://github.com/siberfx/backpack-leafletjs/contributors)

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md) for details.
