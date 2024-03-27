<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQueriesTable extends Migration
{
    public function up()
    {
        Schema::create('user_queries', function (Blueprint $table) {
            $table->id('queryID');
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->string('message', 750); 
            $table->date('date')->default(date('Y-m-d'));
            $table->tinyInteger('action')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_queries');
    }
}

