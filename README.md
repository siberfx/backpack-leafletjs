# Leafletjs Implementation for Laravel Backpack ^4.1

[![Latest Version on Packagist](https://img.shields.io/packagist/dt/siberfx/backpack-leafletjs?style=plastic)](https://packagist.org/packages/siberfx/leafletjs)

## Installation

You can install the package via composer:

```bash
composer require siberfx/backpack-leafletjs
```

## Usage

``` php
// Add SeoCrud to your Backpack Crud Controller
use LeafletFields;

// and call :
$this->setLeafletFields();

// to add default fields
```

``` php
// Add SeoFields to your model
use LeafletFields;
```

You can override SEO fields with override `$seoFields`

To create database you can use migration : 
`php artisan vendor:publish --provider="Siberfx\Leafletjs\LeafletBackpackServiceProvider"`

Add fields in `$fillable` array if you want to save

To set SEO Meta tags :
```php
Leaflet::setTitle($youModel->{\Siberfx\Leafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_ADDRESS});
Leaflet::setTitle($youModel->{\Siberfx\Leafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_LONGITUDE});
Leaflet::setTitle($youModel->{\Siberfx\Leafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_LATITUDE});
```

### Security

If you discover any security related issues, please email info@siberfx.com instead of using the issue tracker.

## Credits

- [Selim Gormus](https://github.com/siberfx)

