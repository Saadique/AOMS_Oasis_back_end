<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{

    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('lecture_id');
            $table->string('name')->nullable();
            $table->double('student_fee')->nullable();
            $table->string('fixed_institute_amount')->nullable();
            $table->integer('teacher_percentage')->nullable();
            $table->string('status')->nullable()->default('active');

            $table->foreign('lecture_id')
                ->references('id')
                ->on('lectures')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
