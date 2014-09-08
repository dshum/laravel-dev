<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_users', function ($table) {
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('password');
			$table->string('fio');
			$table->string('phone');
			$table->string('phone2')->nullable();
			$table->double('discount')->nullable();
			$table->boolean('activated')->nullable();
			$table->boolean('banned')->nullable();
			$table->text('comments')->nullable();
			$table->string('remember_token')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_users');
	}

}
