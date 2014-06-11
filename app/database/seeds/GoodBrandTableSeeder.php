<?php

class GoodBrandTableSeeder extends Seeder {

	public function run()
	{
		DB::table('good_brands')->truncate();

		GoodBrand::create(array(
			'name' => 'Casada',
			'order' => 1,
			'title' => 'Все товары Casada',
			'address' => 'г.Москва, ул. Люблинская, д.1, стр.1, офис 101. Тимофей Голиченков. Тел. 8-926-244-38-95 Е-mail: t.golichenkov@casada.ru',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Medisana',
			'order' => 2,
			'title' => 'Все товары Medisana и Ecomed',
			'address' => 'М. Беляево, ул. Введенского, д 13 Б, стр 1  Магазин-склад Медисана 8(926) 9079506 Самир Мириманов. Кладовщик Елена 8(926) 1371074',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Withings',
			'order' => 3,
			'title' => 'Весы напольные и кухонные. Весы анализаторы',
			'address' => 'Роман Щербаев. Тел: 8(916) 944 94 93',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Homedics',
			'order' => 4,
			'title' => 'Все товары Homedics',
			'address' => 'м. Университет, ул. Минская, База МЧС. Кондратьев Сергей. Тел: 8(495)640-28-70, 8(926)160-63-45.',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Silk\'n',
			'order' => 5,
			'title' => 'Фотоэпиляторы',
			'address' => 'м. Университет, ул. Минская, База МЧС. Кондратьев Сергей. Тел: 8(495)640-28-70, 8(926)160-63-45.',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Hapica',
			'order' => 6,
			'title' => 'Звуковые зубные щетки Hapica',
			'address' => 'м. Академическая, ул.Профсоюзная, дом 2/22. Вход со двора дома в угловом подъезде. Дмитрий Панков. Тел: 8(916) 683-8337',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Smilex',
			'order' => 7,
			'title' => 'Ультразвуковые зубные щетки Smilex',
			'address' => 'м. Академическая, ул.Профсоюзная, дом 2/22. Вход со двора дома в угловом подъезде. Дмитрий Панков. Тел: 8(916) 683-8337',
			'service_section_id' => 14,
		));

		GoodBrand::create(array(
			'name' => 'Powerball',
			'order' => 8,
			'title' => 'Пауэрболы',
			'address' => 'ул. Часовая 28. Ломова Татьяна. Тел: 8(903) 795-9880',
			'service_section_id' => 14,
		));

	}

}
