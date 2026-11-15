<?php

namespace Siberfx\Leafletjs\Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Siberfx\Leafletjs\Tests\TestCase;

class MigrationTest extends TestCase
{
    private function migration(): object
    {
        return require __DIR__.'/../../database/migrations/2023_02_23_011421_add_leaflet_fields.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('places', function (Blueprint $table) {
            $table->id();
        });

        config(['backpack.leaflet.table_name' => 'places']);
    }

    #[Test]
    public function it_adds_nullable_coordinate_columns_to_the_configured_table(): void
    {
        $this->migration()->up();

        $this->assertTrue(Schema::hasColumns('places', ['lat', 'lng']));
    }

    #[Test]
    public function it_removes_the_coordinate_columns_on_rollback(): void
    {
        $this->migration()->up();
        $this->migration()->down();

        $this->assertFalse(Schema::hasColumn('places', 'lat'));
        $this->assertFalse(Schema::hasColumn('places', 'lng'));
    }

    #[Test]
    public function it_explains_which_setting_to_change_when_the_table_is_missing(): void
    {
        config(['backpack.leaflet.table_name' => 'no_such_table']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Set 'backpack.leaflet.table_name' to the table");

        $this->migration()->up();
    }
}
