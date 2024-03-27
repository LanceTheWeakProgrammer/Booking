<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorTable extends Migration
{
    public function up()
    {
        Schema::create('operator', function (Blueprint $table) {
            $table->id('operatorID'); 
            $table->string('serialNumber', 6)->unique(); 
            $table->string('operatorName');
            $table->string('operatorImg');
            $table->string('opAddress');
            $table->string('operatorTel');
            $table->string('operatorEmail');
            $table->string('jobAge');
            $table->text('opDescription'); 
            $table->integer('hRate');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('removed')->default(1);
            $table->timestamps();
        });
        
        DB::unprepared('
            CREATE TRIGGER before_insert_operator
            BEFORE INSERT ON operator
            FOR EACH ROW
            SET NEW.serialNumber = LPAD(FLOOR(100000 + RAND() * 900000), 6, 0);
        ');
    }

    public function down()
    {
        Schema::dropIfExists('operator');
    }
}


