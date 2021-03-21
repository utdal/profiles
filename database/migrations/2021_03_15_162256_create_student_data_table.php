<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id')->unsigned()->nullable();
            $table->string('type');
            $table->integer('sort_order')->unsigned()->nullable()->default(1);
            $table->json('data');
            $table->timestamps();

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
        Schema::dropIfExists('student_data');
    }
}
