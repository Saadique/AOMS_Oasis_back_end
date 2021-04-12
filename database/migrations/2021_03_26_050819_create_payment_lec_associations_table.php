<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentLecAssociationsTable extends Migration
{

    public function up()
    {
        Schema::create('payment_lec_associations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_payment_id')->nullable();
            $table->unsignedInteger('lec_student_ass_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
            $table->string('status')->nullable()->default('active');

            $table->foreign('student_payment_id')
                ->references('id')
                ->on('student__payments')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('cascade');

            $table->foreign('lec_student_ass_id')
                ->references('lecture_student_id')
                ->on('lecture_student')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_lec_associations');
    }
}
