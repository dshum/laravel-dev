<?php namespace LemonTree;

class Tab extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cytrus_tabs';

	public static function getByUser(User $user)
	{
		return
			static::where('user_id', $user->id)->orderBy('id')->get();
	}

}
