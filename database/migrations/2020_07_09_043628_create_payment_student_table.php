<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentStudentTable extends Migration
{

    public function up()
    {
        Schema::create('payment_student', function (Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('payment_id');
            $table->unsignedInteger('scheme_id')->nullable();
            $table->double('payment_amount')->nullable();
            $table->string('payment_month')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('type')->nullable();


            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_student');
    }
}
