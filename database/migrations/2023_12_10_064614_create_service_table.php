<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTable extends Migration
{
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->id('serviceID');
            $table->string('serviceIcon');
            $table->string('serviceType');
            $table->text('sDescription');
            $table->decimal('servicePrice', 8, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('service');
    }

    
}

