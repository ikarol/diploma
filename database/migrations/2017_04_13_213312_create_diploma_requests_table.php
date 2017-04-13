<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiplomaRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diploma_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('diploma_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });

        Schema::table('diploma_requests', function($table) {
            $table->foreign('diploma_id')->references('id')->on('diplomas')->onDelete('cascade');
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
        Schema::dropIfExists('diploma_requests');
    }
}
