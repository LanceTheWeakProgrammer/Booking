<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorCarTable extends Migration
{
    public function up()
    {
        Schema::create('operator_car', function (Blueprint $table) {
            $table->id('entryID');
            $table->unsignedBigInteger('operator_id');
            $table->unsignedBigInteger('car_id');
            $table->timestamps();

            $table->foreign('operator_id')->references('operatorID')->on('operator')->onDelete('cascade');
            $table->foreign('car_id')->references('carID')->on('car')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('operator_car');
    }
}
