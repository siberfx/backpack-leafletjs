<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(config('backpack.leaflet.table_name'), function (Blueprint $table) {
            $table->decimal(config('backpack.leaflet.lat_field'), 10, 7)->nullable();
            $table->decimal(config('backpack.leaflet.lng_field'), 10, 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table(config('backpack.leaflet.table_name'), function (Blueprint $table) {
            $table->dropColumn([
                config('backpack.leaflet.lat_field'),
                config('backpack.leaflet.lng_field'),
            ]);
        });
    }
};
