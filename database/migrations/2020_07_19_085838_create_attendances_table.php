<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('daily_schedule_id');
            $table->string('attendance_status')->nullable();
            $table->timestamps();

            $table->foreign('daily_schedule_id')
                ->references('id')
                ->on('daily_schedules')
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
        Schema::dropIfExists('attendances');
    }
}
