<?php namespace LemonTree;

class OneToOneProperty extends BaseProperty {

	const RESTRICT = 1;
	const CASCADE = 2;
	const SETNULL = 3;

	protected $relatedClass = null;
	protected $deleting = self::RESTRICT;
	protected $parent = false;

	protected $rules = array('integer');

	public static function create($name)
	{
		return new self($name);
	}

	public function getRefresh()
	{
		return true;
	}

	public function setRelatedClass($relatedClass)
	{
		$this->relatedClass = $relatedClass;

		return $this;
	}

	public function getRelatedClass()
	{
		return $this->relatedClass;
	}

	public function setDeleting($deleting)
	{
		if (in_array($deleting, array(
			self::RESTRICT,
			self::CASCADE,
			self::SETNULL,
		))) {
			$this->deleting = $deleting;
		}

		return $this;
	}

	public function getDeleting()
	{
		return $this->deleting;
	}

	public function setParent($parent)
	{
		$this->parent = $parent;

		return $this;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function setElement(Element $element)
	{
		$this->element = $element;

		$class = $this->getRelatedClass();
		$id = $this->element->{$this->getName()};

		try {
			$this->value = $class && $id ? $class::find($id) : null;
		} catch (\Exception $e) {}

		return $this;
	}

	public function getElementListView()
	{
		$site = \App::make('site');

		$relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);
		$mainProperty = $relatedItem->getMainProperty();

		$scope = array(
			'value' => $this->getValue(),
			'mainProperty' => $mainProperty,
		);

		try {
			$view = $this->getClassName().'.elementList';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

	public function getElementEditView()
	{
		$site = \App::make('site');

		$relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);
		$mainProperty = $relatedItem->getMainProperty();

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $this->getValue(),
			'readonly' => $this->getReadonly(),
			'mainProperty' => $mainProperty,
			'relatedClass' => $relatedClass,
		);

		try {
			$view = $this->getClassName().'.elementEdit';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

}