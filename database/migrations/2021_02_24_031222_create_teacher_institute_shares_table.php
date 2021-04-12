<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherInstituteSharesTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_institute_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_payment_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('student_payment_id')->nullable();
            $table->unsignedInteger('lecture_id')->nullable();
            $table->string('status')->nullable();
            $table->double('teacher_amount')->nullable();
            $table->double('institute_amount')->nullable();
            $table->string('delete_status')->nullable()->default('active');

            $table->foreign('monthly_payment_id')
                ->references('id')
                ->on('monthly_payments')
                ->onDelete('cascade');

            $table->foreign('student_payment_id')
                ->references('id')
                ->on('student__payments')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('cascade');

            $table->foreign('lecture_id')
                ->references('id')
                ->on('lectures')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_institute_shares');
    }
}
