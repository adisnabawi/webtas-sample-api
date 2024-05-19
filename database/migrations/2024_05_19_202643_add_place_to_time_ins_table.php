<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaceToTimeInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_ins', function (Blueprint $table) {
            $table->string('place_in')->nullable();
            $table->string('place_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_ins', function (Blueprint $table) {
            $table->dropColumn('place_in');
            $table->dropColumn('place_out');
        });
    }
}
