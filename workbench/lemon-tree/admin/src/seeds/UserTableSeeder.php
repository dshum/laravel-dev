<?php namespace LemonTree;

use Illuminate\Database\Seeder;
use Illuminate\Database\Facades\DB;

class UserTableSeeder extends Seeder {

	public function run()
	{
		\DB::table('cytrus_users')->truncate();

		$user = \Sentry::createUser(array(
			'login' => 'magus',
			'password' => 'test',
			'email' => 'denis-shumeev@yandex.ru',
			'first_name' => 'Denis',
			'last_name' => 'Shumeev',
			'parameters' => null,
			'permissions' => array(),
			'activated' => true,
		));

		 $group = \Sentry::findGroupById(1);

		 $user->addGroup($group);

	}

}
