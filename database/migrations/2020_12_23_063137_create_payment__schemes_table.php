<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment__schemes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('scheme_name');
            $table->unsignedInteger('no_of_subjects');
            $table->double('student_fee');
            $table->double('fixed_institute_amount')->nullable();
            $table->string('class_level')->nullable();
            $table->string('temp')->nullable();
            $table->string('status')->nullable()->default('active');
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
        Schema::dropIfExists('payment__schemes');
    }
}
