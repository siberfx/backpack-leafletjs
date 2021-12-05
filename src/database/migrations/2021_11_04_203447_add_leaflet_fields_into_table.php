<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeafletFieldsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('backpack.leaflet.table_name'), function (Blueprint $table) {
            $table->string(\Siberfx\BackpackLeafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_ADDRESS)->nullable();
            $table->string(\Siberfx\BackpackLeafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_LONGITUDE)->nullable();
            $table->string(\Siberfx\BackpackLeafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_LATITUDE)->nullable();
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
            $table->dropColumn(\Siberfx\BackpackLeafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_ADDRESS);
            $table->dropColumn(\Siberfx\BackpackLeafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_LONGITUDE);
            $table->dropColumn(\Siberfx\BackpackLeafletjs\Models\Interfaces\LeafletFieldsInterface::COLUMN_LATITUDE);
        });
    }
}
