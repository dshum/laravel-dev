<?php

class ExpenseSourceTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		
		DB::table('expense_sources')->truncate();

		ExpenseSource::create(array(
			'name' => 'Безнал',
			'order' => 1,
			'service_section_id' => 13,
		));

		ExpenseSource::create(array(
			'name' => 'Пластиковая карта',
			'order' => 2,
			'service_section_id' => 13,
		));

		ExpenseSource::create(array(
			'name' => 'Наличные',
			'order' => 3,
			'service_section_id' => 13,
		));

	}

}
