<?php

class SiteUserTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();

		DB::table('site_users')->truncate();

		SiteUser::create(array(
			'email' => 'denis-shumeev@yandex.ru',
			'password' => Hash::make('qwerty'),
			'fio' => 'Денис Шумеев',
			'phone' => '+7 926 3937226',
			'activated' => true,
		));

	}

}
