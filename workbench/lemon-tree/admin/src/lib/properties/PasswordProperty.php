<?php namespace LemonTree;

class PasswordProperty extends BaseProperty {

	public static function create($name)
	{
		return new self($name);
	}

	public function set()
	{
		$name = $this->getName();

		if (\Input::has($name) && \Input::get($name)) {
			$this->element->$name = \Input::get($name);
		}

		return $this;
	}

	public function getElementSearchView()
	{
		return null;
	}

}
