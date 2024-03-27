<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('operator_id');
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('dislikes')->default(0);
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->unsignedSmallInteger('flag')->default(0);
            $table->string('flag_reason')->nullable();
            $table->string('flag_status')->nullable();
            $table->text('reason_info')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user_info')->onDelete('cascade');
            $table->foreign('operator_id')->references('operatorID')->on('operator')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
