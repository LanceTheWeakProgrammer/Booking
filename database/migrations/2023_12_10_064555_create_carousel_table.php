<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarouselTable extends Migration
{
    public function up()
    {
        Schema::create('carousel', function (Blueprint $table) {
            $table->id('entryID');
            $table->string('cPicture');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carousel');
    }
}

