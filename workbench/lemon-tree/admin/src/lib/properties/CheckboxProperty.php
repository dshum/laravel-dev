<?php namespace LemonTree;

class CheckboxProperty extends BaseProperty {

	public static function create($name)
	{
		return new self($name);
	}

	public function setElement(Element $element)
	{
		$this->element = $element;

		$value = $element->{$this->getName()};

		$this->value = $value ? true : false;

		return $this;
	}

	public function set()
	{
		$name = $this->getName();

		$value = \Input::has($name) ? true : false;

		$this->element->$name = $value;

		return $this;
	}

}
