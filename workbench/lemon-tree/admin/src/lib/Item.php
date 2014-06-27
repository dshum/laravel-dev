<?php namespace LemonTree;

class Item {

	protected $properties = array();

	protected $name = null;
	protected $title = null;
	protected $mainProperty = null;
	protected $root = false;
	protected $elementPermissions = false;
	protected $binds = false;
	protected $perPage = null;
	protected $orderBy = array();

	public function __construct($name) {
		$this->name = $name;

		return $this;
	}

	public static function create($name)
	{
		return new self($name);
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

	public function setMainProperty($mainProperty)
	{
		$this->mainProperty = $mainProperty;

		return $this;
	}

	public function getMainProperty()
	{
		return $this->mainProperty;
	}

	public function setRoot($root)
	{
		$this->root = $root;

		return $this;
	}

	public function getRoot()
	{
		return $this->root;
	}
	
	public function setElementPermissions($elementPermissions)
	{
		$this->elementPermissions = $elementPermissions;

		return $this;
	}
	
	public function getElementPermissions()
	{
		return $this->elementPermissions;
	}

	public function bindItem($name)
	{
		$this->binds[$name] = $name;

		return $this;
	}

	public function getBinds()
	{
		return $this->binds;
	}

	public function setPerPage($perPage)
	{
		$this->perPage = $perPage;

		return $this;
	}

	public function getPerPage()
	{
		return $this->perPage;
	}

	public function addOrderBy($field, $direction = 'asc')
	{
		$this->orderBy[$field] = $direction;

		return $this;
	}

	public function getOrderByList()
	{
		return $this->orderBy;
	}

	public function getClass()
	{
		return new $this->name;
	}

	public function addProperty(BaseProperty $property)
	{
		$property->setItem($this);

		$this->properties[$property->getName()] = $property;

		return $this;
	}

	public function getPropertyList()
	{
		return $this->properties;
	}

	public function getPropertyByName($name)
	{
		return
			isset($this->properties[$name])
			? $this->properties[$name]
			: null;
	}

}
