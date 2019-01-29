<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('display_name')->nullable();
			$table->string('password')->nullable();
			$table->rememberToken();
			$table->string('department')->nullable();
			$table->string('firstname')->nullable();
			$table->string('lastname')->nullable();
			$table->string('pea')->nullable();
			$table->string('email')->nullable();
			$table->string('title')->nullable();
			$table->string('college')->nullable();
			$table->integer('school_id')->unsigned()->nullable();
			$table->dateTime('last_access')->nullable();
			$table->timestamps();

			$table->foreign('school_id')
					->references('id')->on('schools')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}
}
