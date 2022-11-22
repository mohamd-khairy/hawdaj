<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemToPlacesAndStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('distance')->nullable();
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->string('distance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('distance');
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('distance');
        });
    }
}
