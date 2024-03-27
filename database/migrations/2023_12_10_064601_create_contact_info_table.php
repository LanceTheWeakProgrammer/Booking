<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactInfoTable extends Migration
{
    public function up()
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id('conID');
            $table->string('address');
            $table->string('gmap');
            $table->string('tel1');
            $table->string('tel2');
            $table->string('email');
            $table->string('twt');
            $table->string('fb');
            $table->string('ig');
            $table->text('iframe')->nullable();
        });

        DB::table('contact_info')->insert([
            'conID' => 1,
            'address' => 'XYZ, Automobile Repair Shop',
            'gmap' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345.67890',
            'tel1' => '123-456-7890',
            'tel2' => '987-654-3210',
            'email' => 'info@example.com',
            'twt' => 'https://twitter.com/example',
            'fb' => 'https://www.facebook.com/example',
            'ig' => 'https://www.instagram.com/example',
            'iframe' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.1357473083744!2d123.94710707479705!3d10.250633989867975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99af5a8eeeb1f%3A0x9472f4559fe5506!2sCordova%20Sports%20Complex!5e0!3m2!1sen!2sph!4v1701687180545!5m2!1sen!2sph',
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('contact_info');
    }
}

