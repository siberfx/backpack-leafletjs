# Changelog

All notable changes to `siberfx/backpack-leafletjs` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [7.0.0] - 2026-10-06

### Added

- Support for **Laravel 12 and 13**, **Backpack 6.8+ and 7.x**, and **PHP 8.2 – 8.5**.
- Multi-input field mode: `'name' => 'lat,lng'` renders and saves both coordinate inputs itself, so no extra hidden fields are needed.
- `setLeafletFields()` now accepts `$extras` that are merged into the field definition (label, tab, hint, options…).
- Draggable marker, address search via [leaflet-control-geocoder](https://github.com/perliedman/leaflet-control-geocoder) (Nominatim) and automatic map resizing inside tabs/modals.
- OpenStreetMap tile provider that works without an API key; Mapbox is used when `MAPS_MAPBOX_ACCESS_TOKEN` is set.
- New field options: `zoom`, `height`, `search`.
- New config keys: `default_center`, `default_zoom`, `provider`, `providers`, `assets`.
- `<x-leaflet-frontend>` accepts markers as `[lat, lng]` or `['lat' => …, 'lng' => …, 'popup' => …]` and a `height` attribute.
- PHPUnit test suite (Orchestra Testbench) and GitHub Actions matrix.

### Changed

- Package config is merged automatically — publishing `config/backpack/leaflet.php` is now optional.
- The field view is registered through Backpack's `ViewNamespaces` and the Blade component through `Blade::component()`; nothing has to be copied into the app.
- Package layout follows Laravel conventions (`config/`, `database/`, `resources/views/` at the root).
- Publish tags renamed to `leafletjs-config`, `leafletjs-views` and `leafletjs-migrations`; migrations are published with `publishesMigrations()` so they get a fresh timestamp.
- Migration columns are `decimal(10, 7)` instead of `string`.
- Field JavaScript uses Backpack's `data-init-function` convention, so several maps can live on the same form.
- Leaflet upgraded to 1.9.4, Mapbox style to `streets-v12`.
- `LeafletFrontend` component moved to `Siberfx\Leafletjs\View\Components\LeafletFrontend`.
- Coordinates are only written once the user picks a location — untouched forms no longer save the default centre.

### Removed

- Support for Laravel < 12, Backpack < 6.8 and PHP < 8.2.
- The `autogenerate` field option — use `'name' => 'lat,lng'` instead.
- The unmaintained Esri geocoder (`esri-leaflet` 0.0.1-beta.5) and the `.pointer` overlay.
- The `all`, `config`, `views`, `component` and `migrations` publish tags.

### Fixed

- Service provider auto-discovery on case-sensitive filesystems (`LeafLetServiceProvider` → `LeafletServiceProvider`).
- Migration file did not `return` the anonymous class, so it could not run.
- Frontend component rendered a copy of the CRUD field view and crashed outside Backpack forms.
- Latitude and longitude were swapped in the frontend component.
- Field read hard-coded `lat`/`lng` attributes instead of the configured column names.
- Leaflet's default marker icon 404'd when the stylesheet was served by Basset.

### Upgrading from 6.x

1. `composer require siberfx/backpack-leafletjs:^7.0`
2. Delete previously published copies you have not customised:
   `resources/views/vendor/backpack/crud/fields/leaflet.blade.php`,
   `resources/views/components/leaflet-frontend.blade.php`,
   `app/View/Components/LeafletFrontend.php`.
3. If you published the config, move `mapbox.access_token` to the `MAPS_MAPBOX_ACCESS_TOKEN` env variable (or republish with `--tag=leafletjs-config`).
4. Replace `'autogenerate' => true` fields with a single field named `'lat,lng'`. The map field + two hidden fields setup keeps working, but the single field is simpler.

## [6.1.3] - 2024-10-06

- Versioning update.

## [6.1.1] - 2023-11-08

- Backpack 6.x field updates.

## [2.0] - 2023-07-16

- Backpack 6 support with Basset asset loading.

## [5.0.0] - 2023-02-18

- Backpack 5 support, config-driven table/column names and migration.

## [1.5.0] - 2022-08-30

- Global Leaflet setup.

[7.0.0]: https://github.com/siberfx/backpack-leafletjs/compare/2.1.0...7.0.0
[6.1.3]: https://gitlab.com/siberfx/backpack-leafletjs/-/tags/6.1.3
[6.1.1]: https://gitlab.com/siberfx/backpack-leafletjs/-/tags/6.1.1
[2.0]: https://github.com/siberfx/backpack-leafletjs/releases/tag/2.0
[5.0.0]: https://gitlab.com/siberfx/backpack-leafletjs/-/tags/5.0.0
[1.5.0]: https://gitlab.com/siberfx/backpack-leafletjs/-/tags/1.5.0
