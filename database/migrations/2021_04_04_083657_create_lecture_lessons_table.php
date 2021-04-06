<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLectureLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lecture_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('temp')->nullable();
            $table->string('temp2')->nullable();

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
        Schema::dropIfExists('lecture_lessons');
    }
}
