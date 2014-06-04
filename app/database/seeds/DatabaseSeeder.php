<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('SectionTableSeeder');

		$this->call('ServiceSectionTableSeeder');

		$this->call('SiteSettingsTableSeeder');

		$this->call('CounterTableSeeder');

		$this->call('ExpenseCategoryTableSeeder');

		$this->call('ExpenseSourceTableSeeder');

		$this->call('CategoryTableSeeder');

		$this->call('SubcategoryTableSeeder');

		$this->call('GoodBrandTableSeeder');

		$this->call('GoodTableSeeder');
	}

}
