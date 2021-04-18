<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->string('registration_no')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('name');
            $table->string('student_type');
            $table->string('gender')->nullable();
            $table->string('school_name')->nullable();
            $table->string('NIC')->nullable();
            $table->integer('mobile_no')->nullable();
            $table->integer('parent_contact')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('status')->nullable()->default('active');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('students');
    }
}
