<?php namespace LemonTree;

class Group extends \Cartalyst\Sentry\Groups\Eloquent\Group {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cytrus_groups';

	public function newQuery($excludeDeleted = true)
	{
		$builder = parent::newQuery();

		return $builder->rememberForever();
	}

}
