<?php namespace LemonTree;

class Group extends \Cartalyst\Sentry\Groups\Eloquent\Group {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cytrus_groups';
	protected $pivotTable = 'cytrus_users_groups';

	public static function boot()
	{
		parent::boot();

		static::created(function($element) {
			$element->flush();
		});

		static::saved(function($element) {
			$element->flush();
		});

		static::deleted(function($element) {
			$element->flush();
		});
    }

	public function flush()
	{
		\Cache::tags('Group')->flush();
	}

	public function newQuery($excludeDeleted = true)
	{
		$builder = parent::newQuery();

		return $builder->cacheTags('Group', 'UserGroup')->rememberForever();
	}

	public function users()
	{
		return $this->belongsToMany('LemonTree\User', $this->pivotTable);
	}

}
