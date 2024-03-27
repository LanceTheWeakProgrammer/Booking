<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')
                ->nullable()
                ->constrained('operator', 'operatorID')
                ->onDelete('cascade'); 
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('user_info', 'id')
                ->onDelete('cascade');
            $table->foreignId('admin_id') 
                ->nullable()
                ->constrained('admin_info', 'adminID')
                ->onDelete('cascade'); 
            $table->text('message')->nullable();
            $table->text('operator_message')->nullable();
            $table->text('admin_message')->nullable();
            $table->tinyInteger('isRead')->default(0); 
            $table->tinyInteger('isRead_admin')->default(0); 
            $table->tinyInteger('isRead_operator')->default(0); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
