<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('goods', function ($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('order');
			$table->string('url');
			$table->integer('code');
			$table->double('supplier_price')->nullable();
			$table->double('price')->nullable();
			$table->double('price2')->nullable();
			$table->double('price3')->nullable();
			$table->double('old_price')->nullable();
			$table->string('image')->nullable();
			$table->string('title')->nullable();
			$table->string('meta_keywords');
			$table->text('meta_description');
			$table->text('shortcontent')->nullable();
			$table->mediumText('fullcontent')->nullable();
			$table->boolean('special');
			$table->boolean('novelty');
			$table->boolean('hide');
			$table->boolean('absent');
			$table->integer('category_id')->unsigned()->index();
			$table->integer('subcategory_id')->unsigned()->nullable()->default(null)->index();
			$table->integer('good_brand_id')->unsigned()->index();
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
		Schema::drop('goods');
	}

}
