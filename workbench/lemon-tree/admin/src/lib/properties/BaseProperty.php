<?php namespace LemonTree;

abstract class BaseProperty {

	protected $item = null;
	protected $name = null;
	protected $title = null;

	protected $show = false;
	protected $required = false;
	protected $readonly = false;
	protected $hidden = false;

	protected $element = null;
	protected $value = null;

	protected $rules = array();
	protected $messages = array();

	public function __construct($name)
	{
		$this->name = $name;

		return $this;
	}

	public function getClassName()
	{
		$className = get_class($this);
		$className = explode('\\', $className);
		$className = end($className);

		return $className;
	}

	public function setItem(Item $item)
	{
		$this->item = $item;

		return $this;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function isMainProperty()
	{
		return
			$this->item->getMainProperty() == $this->name
			? true
			: false;
	}

	public function setShow($show)
	{
		$this->show = $show;

		return $this;
	}

	public function getShow()
	{
		return $this->show;
	}

	public function setRequired($required)
	{
		$this->required = $required;

		return $this;
	}

	public function getRequired()
	{
		return $this->required;
	}

	public function setReadonly($readonly)
	{
		$this->readonly = $readonly;

		return $this;
	}

	public function getReadonly()
	{
		return $this->readonly;
	}

	public function setHidden($hidden)
	{
		$this->hidden = $hidden;

		return $this;
	}

	public function getHidden()
	{
		return $this->hidden;
	}

	public function getRefresh()
	{
		return false;
	}

	public function setElement(Element $element)
	{
		$this->element = $element;

		$this->value = $element->{$this->getName()};

		return $this;
	}

	public function getElement()
	{
		return $this->element;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function set()
	{
		$name = $this->getName();

		$value = \Input::get($name);

		if ( ! mb_strlen($value)) $value = null;

		$this->element->$name = $value;

		return $this;
	}

	public function getElementListView()
	{
		$scope = array(
			'value' => $this->getValue(),
		);

		try {
			$view = $this->getClassName().'.elementList';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

	public function getElementEditView()
	{
		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $this->getValue(),
			'readonly' => $this->getReadonly(),
		);

		try {
			$view = $this->getClassName().'.elementEdit';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

	public function addRule($rule)
	{
		$this->rules[$rule] = $rule;

		return $this;
	}

	public function getRules()
	{
		return $this->rules;
	}

	protected function setter()
	{
		return 'set'.ucfirst($this->getName());
	}

	protected function getter()
	{
		return 'get'.ucfirst($this->getName());
	}

}
