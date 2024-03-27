<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarTable extends Migration
{
    public function up()
    {
        Schema::create('car', function (Blueprint $table) {
            $table->id('carID');
            $table->string('carName');
            $table->string('carModel');
            $table->string('carType');
        });

    }

    public function down()
    {
        Schema::dropIfExists('car');
    }
}

