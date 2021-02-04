<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_payment_id')->nullable();
            $table->date('due_date')->nullable();
            $table->date('amount')->nullable();
            $table->string('month')->nullable();
            $table->year('year')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('alert_status')->nullable();
            $table->string('status')->nullable();
            $table->string('teacher_remuneration_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_payments');
    }
}
