<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamInfoTable extends Migration
{
    public function up()
    {
        Schema::create('team_info', function (Blueprint $table) {
            $table->id('teamID');
            $table->string('mName');
            $table->string('mTitle');
            $table->string('mPicture');
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_info');
    }
}

