<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodBrandTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('good_brands', function ($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('order');
			$table->integer('service_section_id')->unsigned()->index();
			$table->string('title')->nullable();
			$table->string('h1')->nullable();
			$table->mediumText('fullcontent')->nullable();
			$table->text('address')->nullable();
			$table->boolean('hide')->nullable();
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
		Schema::drop('good_brands');
	}

}
