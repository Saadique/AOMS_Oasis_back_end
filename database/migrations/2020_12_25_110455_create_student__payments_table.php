<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student__payments', function (Blueprint $table) {

            $table->id();
            $table->unsignedInteger('student_id');
            $table->string('payment_type')->nullable();//scheme or normal
            $table->unsignedInteger('payment_scheme_id')->nullable();
            $table->unsignedInteger('payment_id')->nullable();
            $table->double('payment_amount')->nullable();
            $table->date('payment_start_date')->nullable();
            $table->date('payment_end_date')->nullable();
            $table->string('status')->nullable()->default('active');


            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('payment_scheme_id')
                ->references('id')
                ->on('payment__schemes')
                ->onDelete('cascade');

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
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
        Schema::dropIfExists('student__payments');
    }
}
