<?php

use App\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type')->default(Subject::$COMPULSORY);
            $table->unsignedInteger('course_medium_id');
            $table->string('subject_type')->nullable();
            $table->timestamps();

            $table->foreign('course_medium_id')
                ->references('id')
                ->on('course_medium')
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
        Schema::dropIfExists('subjects');
    }
}
