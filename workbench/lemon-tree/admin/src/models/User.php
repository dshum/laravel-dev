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
		\Cache::tags('User', 'Group')->flush();
	}

	public function newQuery($excludeDeleted = true)
	{
		$builder = parent::newQuery();

		return $builder->cacheTags('User', 'UserGroup')->rememberForever();
	}

	public function getUnserializedParameters()
		{
			try {
				return unserialize($this->parameters);
			} catch (\Exception $e) {}

			return null;
		}

		public function parameterExists($name)
		{
			$unserializedParameters = $this->getUnserializedParameters();

			return isset($unserializedParameters[$name]);
		}

		public function getParameter($name)
		{
			$unserializedParameters = $this->getUnserializedParameters();

			return
				isset($unserializedParameters[$name])
				? $unserializedParameters[$name]
				: null;
		}

		public function setParameter($name, $value)
		{
			$unserializedParameters = $this->getUnserializedParameters();

			$unserializedParameters[$name] = $value;

			$parameters = serialize($unserializedParameters);

			$this->parameters = $parameters;

			$this->save();

			return $this;
		}

		public function getTabs()
		{
			return Tab::getByUser($this);
		}

}
