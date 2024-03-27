<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteRatingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('website_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->integer('webRating'); 
            $table->string('comment')->nullable();
            $table->tinyInteger('display')->default(0);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('website_ratings');
    }
}
