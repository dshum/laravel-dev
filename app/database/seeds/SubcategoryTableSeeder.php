<?php

class SubcategoryTableSeeder extends Seeder {

	public function run()
	{
		DB::table('subcategories')->truncate();

		Subcategory::create(array(
			'name' => 'Массажные кресла',
			'order' => 1,
			'url' => 'chairs',
			'title' => 'Купить массажное кресло для дома и офиса, цены, отзывы, доставка по Москве — бесплатно',
			'fullcontent' => null,
			'category_id' => 1,
			'hide' => false,
		));

		Subcategory::create(array(
			'name' => 'Массажные накидки',
			'order' => 2,
			'url' => 'covers',
			'title' => 'Массажная накидка на кресло и сиденье автомобиля, купить в интернет магазине GoToHealth.ru',
			'fullcontent' => null,
			'category_id' => 1,
			'hide' => false,
		));

		Subcategory::create(array(
			'name' => 'Массажные подушки',
			'order' => 3,
			'url' => 'pillows',
			'title' => 'Массажная подушка для спины, купить подушку для автомобиля, подушки Шиацу',
			'fullcontent' => null,
			'category_id' => 1,
			'hide' => false,
		));

	}

}
