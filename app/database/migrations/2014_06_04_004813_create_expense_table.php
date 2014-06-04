<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function ($table) {
			$table->increments('id');
			$table->string('name');
			$table->double('sum');
			$table->text('comment')->nullable();
			$table->integer('expense_category_id')->unsigned()->index();
			$table->integer('expense_source_id')->unsigned()->index();
			$table->integer('service_section_id')->unsigned()->index();
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
		Schema::drop('expenses');
	}

}
