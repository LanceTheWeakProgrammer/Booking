<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id('siteID');
            $table->string('siteTitle');
            $table->text('siteAbout');
            $table->tinyInteger('shutdown');
        });

        DB::table('settings')->insert([
            'siteID' => 1,
            'siteTitle' => 'STARLIGHT',
            'siteAbout' => 'Hello world',
            'shutdown' => 0,
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}

