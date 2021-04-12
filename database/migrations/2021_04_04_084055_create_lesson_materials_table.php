<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedInteger('lecture_id')->nullable();
            $table->string('path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_extension')->nullable();
            $table->string('unique_name')->nullable();
            $table->string('status')->nullable()->default('active');

            $table->foreign('lesson_id')
                ->references('id')
                ->on('lecture_lessons')
                ->onDelete('cascade');

            $table->foreign('lecture_id')
                ->references('id')
                ->on('lectures')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_materials');
    }
}
