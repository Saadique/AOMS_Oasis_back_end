<?php

use App\Lecture;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->integer('no_of_students')->nullable();
            $table->float('fee_amount')->nullable();
            $table->string('type')->default(Lecture::$NORMAL_CLASS);
            $table->string('class_type')->nullable();
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('course_medium_id');
            $table->unsignedInteger('subject_id');
            $table->timestamps();

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('cascade');

            $table->foreign('course_medium_id')
                ->references('id')
                ->on('course_medium')
                ->onDelete('cascade');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
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
        Schema::dropIfExists('lectures');
    }
}
