<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_payment_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->date('due_date')->nullable();
            $table->double('amount')->nullable();
            $table->string('month')->nullable();
            $table->year('year')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('alert_status')->nullable();
            $table->string('status')->nullable();
            $table->string('teacher_remuneration_status')->nullable();
            $table->timestamps();

            $table->foreign('student_payment_id')
                ->references('id')
                ->on('student__payments')
                ->onDelete('cascade');

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_payments');
    }
}
