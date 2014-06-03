<?php namespace LemonTree;

class FloatProperty extends BaseProperty {

	protected $rules = array('numeric');

	public static function create($name)
	{
		return new self($name);
	}

}
