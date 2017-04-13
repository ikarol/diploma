<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_works', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('professor_id')->unsigned();
            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('discipline_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('technologies')->nullable();
            $table->integer('mark')->nullable();
            $table->timestamps();
        });

        Schema::table('course_works', function($table) {
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_works');
    }
}
