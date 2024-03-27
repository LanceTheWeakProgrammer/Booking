<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateAdminInfoTable extends Migration
{
    public function up()
    {
        Schema::create('admin_info', function (Blueprint $table) {
            $table->id('adminID');
            $table->string('adminUsername');
            $table->string('adminPassword');
            $table->timestamps();
        });

        $adminData = [
            'adminUsername' => 'Lance',
            'adminPassword' => Hash::make('password'), 
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('admin_info')->insert($adminData);
    }

    public function down()
    {
        Schema::dropIfExists('admin_info');
    }
}

