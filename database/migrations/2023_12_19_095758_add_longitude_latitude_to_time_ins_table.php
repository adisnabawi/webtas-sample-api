<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLongitudeLatitudeToTimeInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_ins', function (Blueprint $table) {
            $table->string('latitude_in')->nullable();
            $table->string('longitude_in')->nullable();
            $table->string('latitude_out')->nullable();
            $table->string('longitude_out')->nullable();
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
            $table->dropColumn('latitude_in');
            $table->dropColumn('longitude_in');
            $table->dropColumn('latitude_out');
            $table->dropColumn('longitude_out');
        });
    }
};
