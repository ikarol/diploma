<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseWorkRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_work_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_work_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });

        Schema::table('course_work_requests', function($table) {
            $table->foreign('course_work_id')->references('id')->on('course_works')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_work_requests');
    }
}
