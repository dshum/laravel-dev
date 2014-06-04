<?php

class CategoryTableSeeder extends Seeder {

	public function run()
	{
		DB::table('categories')->truncate();

		Category::create(array(
			'name' => 'Массажеры',
			'order' => 1,
			'title' => 'Массажеры',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Весы напольные и кухонные',
			'order' => 2,
			'title' => 'Весы напольные и кухонные',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Ультразвуковые зубные щетки',
			'order' => 3,
			'title' => 'Ультразвуковые зубные щетки',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Пауэрболы',
			'order' => 4,
			'title' => 'Пауэрболы',
			'shortcontent' => '',
			'fullcontent' => '',
		));

		Category::create(array(
			'name' => 'Фотоэпиляторы',
			'order' => 5,
			'title' => 'Фотоэпиляторы',
			'shortcontent' => '',
			'fullcontent' => '',
		));

	}

}
