<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfoTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('contactNumber');
            $table->string('address');
            $table->string('zipcode');
            $table->string('gender');
            $table->date('birthdate');
            $table->string('password');
            $table->string('picture')->nullable();
            $table->tinyInteger('isVerified')->default(0);
            $table->boolean('is_online')->default(false);
            $table->string('token')->nullable(); 
            $table->string('t_expire')->nullable(); 
            $table->dateTime('datentime')->default(now()); 
            $table->tinyInteger('status')->default(1);
            $table->string('flag')->default('Good');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('user_info');
    }
};

