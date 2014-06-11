<?php

class SiteSettingsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('site_settings')->truncate();

		SiteSettings::create(array(
			'name' => 'Настройки сайта',
			'order' => 1,
			'title' => 'Товары и гаджеты для здорового образа жизни &#151; интернет-магазин Trilobite Group',
			'meta_keywords' => 'Trilobite Group',
			'meta_description' => 'Trilobite Group',
			'site_name' => 'Trilobite Group',
			'phone' => '+7 495 99-181-55 ',
		));

	}

}
