<?php

class ExpenseCategoryTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		
		DB::table('expense_categories')->truncate();

		ExpenseCategory::create(array(
			'name' => 'Зарплата',
			'order' => 1,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Транспорт',
			'order' => 21,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Реклама',
			'order' => 3,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Связь',
			'order' => 4,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Офисное',
			'order' => 5,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Налоги',
			'order' => 6,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Аренда',
			'order' => 7,
			'service_section_id' => 12,
		));

		ExpenseCategory::create(array(
			'name' => 'Прочее',
			'order' => 8,
			'service_section_id' => 12,
		));

	}

}
