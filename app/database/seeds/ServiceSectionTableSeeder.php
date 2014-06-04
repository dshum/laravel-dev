<?php

class ServiceSectionTableSeeder extends Seeder {

	public function run()
	{
		DB::table('service_sections')->truncate();

		ServiceSection::create(array(
			'name' => 'Справочники',
			'order' => 1,
		));

		ServiceSection::create(array(
			'name' => 'Заказы',
			'order' => 2,
		));

		ServiceSection::create(array(
			'name' => 'Покупатели',
			'order' => 3,
		));

		ServiceSection::create(array(
			'name' => 'Расходы',
			'order' => 4,
		));

		ServiceSection::create(array(
			'name' => 'Счетчики',
			'order' => 5,
		));

		ServiceSection::create(array(
			'name' => 'Инструменты',
			'order' => 6,
		));

		ServiceSection::create(array(
			'name' => 'Статистика',
			'order' => 7,
		));

		ServiceSection::create(array(
			'name' => 'Выручка',
			'order' => 8,
			'service_section_id' => 7,
		));

		ServiceSection::create(array(
			'name' => 'Лидеры продаж',
			'order' => 9,
			'service_section_id' => 7,
		));

		ServiceSection::create(array(
			'name' => 'Повторные заказы',
			'order' => 10,
			'service_section_id' => 7,
		));

		ServiceSection::create(array(
			'name' => 'Список адресов для рассылки',
			'order' => 11,
			'service_section_id' => 7,
		));

		ServiceSection::create(array(
			'name' => 'Категории расходов',
			'order' => 12,
			'service_section_id' => 1,
		));

		ServiceSection::create(array(
			'name' => 'Источники расходов',
			'order' => 13,
			'service_section_id' => 1,
		));

		ServiceSection::create(array(
			'name' => 'Бренды товаров',
			'order' => 14,
			'service_section_id' => 1,
		));

	}

}
