<?php namespace LemonTree;

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder {

	public function run()
	{
		\DB::table('cytrus_users_groups')->truncate();
		
		\DB::table('cytrus_groups')->truncate();

		\Sentry::createGroup(array(
			'name'        => 'Системные пользователи',
			'permissions' => array(
				'is_admin' => 1,
				'element.create' => 1,
				'element.owner.view' => 1,
				'element.group.view' => 1,
				'element.world.view' => 1,
				'element.owner.view' => 1,
				'element.group.update' => 1,
				'element.world.update' => 1,
				'element.owner.delete' => 1,
				'element.group.delete' => 1,
				'element.world.delete' => 1,
			),
		));

	}

}
