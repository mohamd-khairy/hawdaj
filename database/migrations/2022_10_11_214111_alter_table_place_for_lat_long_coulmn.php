<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePlaceForLatLongCoulmn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
        });

        Schema::table('opinions', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('opinions', function (Blueprint $table) {
            $table->string('email')->nullable();
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
        });
    }
}
