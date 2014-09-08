<?php namespace LemonTree;

class OrderProperty extends BaseProperty {

	public static function create($name)
	{
		return new self($name);
	}
	
	public function setItem(Item $item)
	{
		$item->setOrderProperty($this->name);
		
		parent::setItem($item);

		return $this;
	}
	
	public function getTitle()
	{
		return 'Порядок';
	}

	public function set()
	{
		$name = $this->getName();

		try {
			$maxOrder = $this->getItem()->getClass()->max($name);
			$this->element->$name = $maxOrder + 1;
		} catch (\Exception $e) {
			$this->element->$name = 1;
		}

		return $this;
	}

}
