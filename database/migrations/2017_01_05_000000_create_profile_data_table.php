<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_data', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('profile_id')->unsigned()->nullable();
            $table->string('type');
            $table->integer('sort_order')->unsigned()->nullable()->default(1);
            $table->json('data');
            $table->boolean('public')->default(1);
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profiles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_data');
    }
}
