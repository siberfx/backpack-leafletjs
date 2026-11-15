<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->table(), function (Blueprint $table) {
            $table->decimal(config('backpack.leaflet.lat_field'), 10, 7)->nullable();
            $table->decimal(config('backpack.leaflet.lng_field'), 10, 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->table(), function (Blueprint $table) {
            $table->dropColumn([
                config('backpack.leaflet.lat_field'),
                config('backpack.leaflet.lng_field'),
            ]);
        });
    }

    /**
     * The migration adds columns to an existing table, so fail with a clear message
     * when `backpack.leaflet.table_name` does not point at one.
     */
    private function table(): string
    {
        $table = config('backpack.leaflet.table_name');

        if (! is_string($table) || $table === '' || ! Schema::hasTable($table)) {
            throw new \RuntimeException(
                "The leaflet migration needs an existing table. Set 'backpack.leaflet.table_name' to the table that stores your coordinates (currently [{$table}])."
            );
        }

        return $table;
    }
};
