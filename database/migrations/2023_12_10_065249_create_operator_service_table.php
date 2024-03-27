<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorServiceTable extends Migration
{
    public function up()
    {
        Schema::create('operator_service', function (Blueprint $table) {
            $table->id('entryID');
            $table->unsignedBigInteger('operator_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamps();

            $table->foreign('operator_id')->references('operatorID')->on('operator')->onDelete('cascade');
            $table->foreign('service_id')->references('serviceID')->on('service')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('operator_service');
    }
}