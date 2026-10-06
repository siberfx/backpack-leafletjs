<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use PHPUnit\Framework\Attributes\Test;
use Siberfx\Leafletjs\Tests\Fixtures\Place;
use Siberfx\Leafletjs\Tests\TestCase;

class LeafletFieldTest extends TestCase
{
    private function renderField(array $field): string
    {
        $crud = new CrudPanel;
        $crud->setModel(Place::class);

        return view('leafletjs::fields.leaflet', [
            'crud' => $crud,
            'field' => $field + ['type' => 'leaflet', 'label' => 'Location'],
        ])->render();
    }

    #[Test]
    public function it_renders_its_own_inputs_for_a_multi_input_name(): void
    {
        $html = $this->renderField(['name' => 'lat,lng', 'value' => ['52.3700000', '4.8900000']]);

        $this->assertStringContainsString('data-init-function="bpFieldInitLeafletElement"', $html);
        $this->assertStringContainsString('name="lat" value="52.3700000"', $html);
        $this->assertStringContainsString('name="lng" value="4.8900000"', $html);
        $this->assertStringContainsString('data-lat-input="#leaflet-lat-lng-lat"', $html);
    }

    #[Test]
    public function it_targets_external_inputs_for_a_legacy_single_name(): void
    {
        $html = $this->renderField(['name' => 'leafletMapId']);

        $this->assertStringContainsString('data-lat-input="#leafletMapId-lat"', $html);
        $this->assertStringNotContainsString('type="hidden"', $html);
    }
}
