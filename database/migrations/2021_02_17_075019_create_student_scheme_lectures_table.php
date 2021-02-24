<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSchemeLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_scheme_lectures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id')->nullable();
            $table->unsignedBigInteger('student_payment_id')->nullable();
            $table->unsignedInteger('payment_scheme_id')->nullable();
            $table->unsignedInteger('lecture_id')->nullable();


            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('student_payment_id')
                ->references('id')
                ->on('student__payments')
                ->onDelete('cascade');

            $table->foreign('payment_scheme_id')
                ->references('id')
                ->on('payment__schemes')
                ->onDelete('cascade');

            $table->foreign('lecture_id')
                ->references('id')
                ->on('lectures')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_scheme_lectures');
    }
}
