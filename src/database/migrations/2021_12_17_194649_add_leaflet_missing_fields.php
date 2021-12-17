<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeafletMissingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('backpack.leaflet.table_name'), function (Blueprint $table) {
            $table->string(config('backpack.leaflet.lat_field'))->nullable();
            $table->string(config('backpack.leaflet.lng_field'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('backpack.leaflet.table_name'), function (Blueprint $table) {
            $table->dropColumn(config('backpack.leaflet.lat_field'));
            $table->dropColumn(config('backpack.leaflet.lng_field'));
        });
    }
}
