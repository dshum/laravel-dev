<?php namespace LemonTree;

class User extends \Cartalyst\Sentry\Users\Eloquent\User {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cytrus_users';

	public static function boot()
	{
		parent::boot();

		static::created(function($element) {
			\Cache::tags('User')->flush();
		});

		static::saved(function($element) {
			\Cache::tags('User')->flush();
		});

		static::deleted(function($element) {
			\Cache::tags('User')->flush();
		});
    }

	public function newQuery($excludeDeleted = true)
	{
		$builder = parent::newQuery();

		return $builder->cacheTags('User')->rememberForever();
	}

}
