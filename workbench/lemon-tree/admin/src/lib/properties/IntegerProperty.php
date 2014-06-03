<?php namespace LemonTree;

class IntegerProperty extends BaseProperty {

	protected $rules = array('integer');

	public static function create($name)
	{
		return new self($name);
	}

}
