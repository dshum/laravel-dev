<?php

class SubcategoryTableSeeder extends Seeder {

	public function run()
	{
		DB::table('subcategories')->truncate();

		Subcategory::create(array(
			'name' => 'Массажные кресла',
			'order' => 1,
			'title' => 'Купить массажное кресло для дома и офиса, цены, отзывы, доставка по Москве — бесплатно',
			'fullcontent' => '',
			'category_id' => 1,
		));

		Subcategory::create(array(
			'name' => 'Массажные накидки',
			'order' => 2,
			'title' => 'Массажная накидка на кресло и сиденье автомобиля, купить в интернет магазине GoToHealth.ru',
			'fullcontent' => '',
			'category_id' => 1,
		));

		Subcategory::create(array(
			'name' => 'Массажные подушки',
			'order' => 3,
			'title' => 'Массажная подушка для спины, купить подушку для автомобиля, подушки Шиацу',
			'fullcontent' => '',
			'category_id' => 1,
		));

	}

}
