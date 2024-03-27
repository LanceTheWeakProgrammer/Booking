<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')
                  ->constrained('operator', 'operatorID')
                  ->onDelete('cascade'); 
            $table->foreignId('user_info_id')
                  ->constrained('user_info', 'id')
                  ->onDelete('cascade'); 
            $table->string('booking_ticket', 7)->nullable()->unique();
            $table->date('start_time');
            $table->date('end_time');
            $table->text('additional_info')->nullable(); 
            $table->string('car_type');
            $table->string('service_type');
            $table->decimal('service_price', 10, 2); 
            $table->decimal('discount_price', 10, 2)->nullable(); 
            $table->string('status');
            $table->boolean('isapproved')->default(0);
            $table->smallInteger('isActive')->default(0);
            $table->text('resched_info')->nullable();
            $table->date('resched_start_time')->nullable(); 
            $table->date('resched_end_time')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        }); 

        DB::unprepared('
            CREATE TRIGGER before_insert_booking_ticket
            BEFORE INSERT ON bookings
            FOR EACH ROW
            SET NEW.booking_ticket = LPAD(FLOOR(100000 + RAND() * 900000), 7, 0);
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_booking_ticket');
    }
}


