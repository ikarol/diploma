<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiplomasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diplomas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('professor_id')->unsigned();
            $table->integer('student_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('technologies')->nullable();
            $table->integer('mark')->nullable();
            $table->timestamps();
        });

        Schema::table('diplomas', function($table) {
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
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
        Schema::dropIfExists('diplomas');
    }
}
