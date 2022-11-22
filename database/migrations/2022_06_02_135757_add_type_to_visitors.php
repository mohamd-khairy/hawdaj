<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToVisitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->enum('type', ['visitor', 'contractor'])->default('visitor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}