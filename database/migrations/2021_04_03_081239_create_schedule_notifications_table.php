<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('daily_schedule_id')->nullable();
            $table->unsignedInteger('lecture_id')->nullable();
            $table->string('action')->nullable();
            $table->string('mail_status')->nullable();
            $table->string('sms_status')->nullable();
            $table->date('old_date')->nullable();
            $table->string('old_start_time')->nullable();
            $table->string('old_end_time')->nullable();
            $table->unsignedInteger('old_room_id')->nullable();
            $table->string('status')->nullable()->default('active');

            $table->foreign('daily_schedule_id')
                ->references('id')
                ->on('daily_schedules')
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
        Schema::dropIfExists('schedule_notifications');
    }
}
