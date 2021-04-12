<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseMediumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_medium', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('course_id')->unsigned();
            $table->unsignedInteger('medium_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('course_medium_type')->nullable();
            $table->string('status')->nullable()->default('active');

            $table->unique(['course_id', 'medium_id']);

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('medium_id')
                ->references('id')
                ->on('mediums')
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
        Schema::dropIfExists('course_medium');
    }
}
