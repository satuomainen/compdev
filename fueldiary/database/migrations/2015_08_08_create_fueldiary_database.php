<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FueldiaryDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ta_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registration');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('ta_users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'registration']);
        });

        Schema::create('ta_fillups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->foreign('vehicle_id')->references('id')->on('ta_vehicles')->onDelete('cascade');
            $table->date('fillup_date');
            $table->decimal('litres');
            $table->decimal('amount_paid');
            $table->integer('mileage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ta_fillups');
        Schema::drop('ta_vehicles');
    }
}
