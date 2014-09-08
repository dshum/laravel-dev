<?php

class CategoryTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		
		DB::table('categories')->truncate();

		Category::create(array(
			'name' => 'Массажеры',
			'order' => 1,
			'url' => 'massage',
			'title' => 'Массажеры',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Весы напольные и кухонные',
			'order' => 2,
			'url' => 'scales',
			'title' => 'Весы напольные и кухонные',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Ультразвуковые зубные щетки',
			'order' => 3,
			'url' => 'tooth-brushes',
			'title' => 'Ультразвуковые зубные щетки',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Пауэрболы',
			'order' => 4,
			'url' => 'powerballs',
			'title' => 'Пауэрболы',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Фотоэпиляторы',
			'order' => 5,
			'url' => 'epilators',
			'title' => 'Фотоэпиляторы',
			'shortcontent' => '',
			'fullcontent' => '',
		));

	}

}
