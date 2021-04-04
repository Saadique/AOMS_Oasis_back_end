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
            $table->string('message')->nullable();
            $table->string('mail_status')->nullable();
            $table->string('sms_status')->nullable();

            $table->foreign('daily_schedule_id')
                ->references('id')
                ->on('daily_schedules')
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
