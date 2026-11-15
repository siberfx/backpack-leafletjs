<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use PHPUnit\Framework\Attributes\Test;
use Siberfx\Leafletjs\Http\Controllers\Admin\Traits\LeafletCrud;
use Siberfx\Leafletjs\Tests\Fixtures\Place;
use Siberfx\Leafletjs\Tests\TestCase;

class LeafletCrudTest extends TestCase
{
    private function controller(): object
    {
        $crud = new CrudPanel;
        $crud->setModel(Place::class);

        // Backpack's field helpers resolve the active panel from the container.
        $this->app->instance('crud', $crud);

        return new class($crud)
        {
            use LeafletCrud;

            public function __construct(public CrudPanel $crud) {}

            public function addLeaflet(array $extras = []): void
            {
                $this->setLeafletFields($extras);
            }
        };
    }

    #[Test]
    public function it_adds_a_leaflet_field_for_the_configured_coordinate_columns(): void
    {
        $controller = $this->controller();
        $controller->addLeaflet();

        $field = $controller->crud->fields()['lat,lng'];

        $this->assertSame('leaflet', $field['type']);
        $this->assertSame('lat,lng', $field['name']);
        $this->assertSame('Location', $field['label']);
    }

    #[Test]
    public function it_uses_custom_coordinate_columns_from_config(): void
    {
        config(['backpack.leaflet.lat_field' => 'latitude', 'backpack.leaflet.lng_field' => 'longitude']);

        $controller = $this->controller();
        $controller->addLeaflet();

        $this->assertArrayHasKey('latitude,longitude', $controller->crud->fields());
    }

    #[Test]
    public function it_merges_extra_field_attributes(): void
    {
        $controller = $this->controller();
        $controller->addLeaflet(['tab' => 'General', 'label' => 'Office', 'options' => ['zoom' => 9]]);

        $field = $controller->crud->fields()['lat,lng'];

        $this->assertSame('General', $field['tab']);
        $this->assertSame('Office', $field['label']);
        $this->assertSame(9, $field['options']['zoom']);
        $this->assertArrayHasKey('provider', $field['options'], 'defaults are kept when extras are merged');
    }
}
