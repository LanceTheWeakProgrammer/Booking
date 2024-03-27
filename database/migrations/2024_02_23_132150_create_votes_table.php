<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rating_id');
            $table->enum('vote_type', ['like', 'dislike']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user_info')->onDelete('cascade');
            $table->foreign('rating_id')->references('id')->on('ratings')->onDelete('cascade');

            $table->unique(['user_id', 'rating_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
