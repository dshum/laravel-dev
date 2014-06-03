<?php namespace LemonTree;

abstract class Element extends \Eloquent {

	const ID_SEPARATOR = '.';

	protected static $cache = true;
	protected static $map = array();

	protected $softDelete = true;
	protected $href = null;

	public static function boot()
	{
		parent::boot();

		static::deleting(function($element) {

			$childItemList = $element->getChildItemList();

			if (isset($childItemList[OneToOneProperty::RESTRICT])) {
				foreach ($childItemList[OneToOneProperty::RESTRICT] as $itemName => $data) {
					foreach ($data as $propertyName => $property) {
						if ( ! $element->isSoftDeleting()) {
							$count = $element->
								hasMany($itemName, $propertyName)->
								onlyTrashed()->
								count();
						} else {
							$count = $element->
								hasMany($itemName, $propertyName)->
								count();
						}
						if ($count > 0) return false;
					}
				}
			} elseif (isset($childItemList[OneToOneProperty::CASCADE])) {
				foreach ($childItemList[OneToOneProperty::CASCADE] as $itemName => $data) {
					foreach ($data as $propertyName => $property) {
						if ( ! $element->isSoftDeleting()) {
							$count = $element->
								hasMany($itemName, $propertyName)->
								onlyTrashed()->
								count();
							if ($count > 0) {
								$result = $element->
									hasMany($itemName, $propertyName)->
									withTrashed()->
									forceDelete();
								if ( ! $result) return false;
							}
						} else {
							$count = $element->
								hasMany($itemName, $propertyName)->
								count();
							if ($count > 0) {
								$result = $element->
									hasMany($itemName, $propertyName)->
									delete();
								if ( ! $result) return false;
							}
						}
					}
				}
			} elseif (isset($childItemList[OneToOneProperty::SETNULL])) {
				foreach ($childItemList[OneToOneProperty::SETNULL] as $itemName => $data) {
					foreach ($data as $propertyName => $property) {
						if ($property->getRequired()) return false;
						if ( ! $element->isSoftDeleting()) {
							$element->
								hasMany($itemName, $propertyName)->
								onlyTrashed()->
								update(array($propertyName => null));
						} else {
							$element->
								hasMany($itemName, $propertyName)->
								update(array($propertyName => null));
						}
					}
				}
			}

		});

		static::created(function($element) {

			$class = get_class($element);
			if ($class::isCache() === true) {
				\Cache::tags($class)->flush();
			}

		});

		static::saved(function($element) {

			$class = get_class($element);
			if ($class::isCache() === true) {
				$key = $class::getCacheKey($element->id);
				\Cache::forget($key);
				\Cache::tags($class)->flush();
			}

		});

		static::deleted(function($element) {

			$class = get_class($element);
			if ($class::isCache() === true) {
				$key = $class::getCacheKey($element->id);
				\Cache::forget($key);
				\Cache::tags($class)->flush();
			}

		});
    }

	public static function isCache()
	{
		return static::$cache;
	}

	public static function find($id, $columns = array('*'))
	{
		if (is_array($id) && empty($id)) return new \Collection;

		$instance = new static;

		$class = get_called_class();

		if (isset(static::$map[$class][$id])) {

			return static::$map[$class][$id];

		} elseif (static::$cache == true) {

			$key = static::getCacheKey($id);

			$result = $instance->newQuery()->rememberForever($key)->
				find($id, $columns);

			static::$map[$class][$id] = $result;

		} else {

			$result = $instance->newQuery()->find($id, $columns);

		}

		static::$map[$class][$id] = $result;

		return $result;
	}

	public static function getCacheKey($id)
	{
		return get_called_class().'.'.$id;
	}

	public function getClass()
	{
		return get_class($this);
	}

	public function getClassId()
	{
		return
			$this->getClass()
			.static::ID_SEPARATOR
			.$this->id;
	}

	public function getItem()
	{
		$site = \App::make('site');

		$class = $this->getClass();

		$item = $site->getItemByName($class);

		return $item;
	}

	public static function getByClassId($classId)
	{
		try {
			list($class, $id) = explode(static::ID_SEPARATOR, $classId);
			return $class::find($id);
		} catch (\Exception $e) {}

		return null;
	}

	public function setParent(Element $parent)
	{
		$item = $this->getItem();

		$propertyList = $item->getPropertyList();

		foreach ($propertyList as $propertyName => $property) {
			if (
				$property instanceof OneToOneProperty
				&& $property->getRelatedClass() == $parent->getClass()
			) {
				$this->$propertyName = $parent->id;
			}
		}

		return $this;
	}

	public function getParent()
	{
		$item = $this->getItem();

		$propertyList = $item->getPropertyList();

		foreach ($propertyList as $propertyName => $property) {
			if (
				$property instanceof OneToOneProperty
				&& $property->getRelatedClass()
				&& $property->getParent()
				&& $this->$propertyName
			) {
				return
					$this->belongsTo($property->getRelatedClass(), $propertyName)->
					cacheTags($property->getRelatedClass())->
					rememberForever()->
					first();
			}
		}

		foreach ($propertyList as $propertyName => $property) {
			if (
				$property instanceof OneToOneProperty
				&& $property->getRelatedClass()
				&& $this->$propertyName
			) {
				return
					$this->belongsTo($property->getRelatedClass(), $propertyName)->
					cacheTags($item->getName())->
					rememberForever()->
					first();
			}
		}

		return null;
	}

	public function getParentList()
	{
		$parentList = array();

		$count = 0;
		$parent = $this->getParent();

		while ($count < 100 && $parent instanceof Element) {
			$parentList[] = $parent;
			$parent = $parent->getParent();
			$count++;
		}

		krsort($parentList);

		return $parentList;
	}

	public function getChildItemList()
	{
		$childItemList = array();

		$site = \App::make('site');

		$class = $this->getClass();

		$itemList = $site->getItemList();

		foreach ($itemList as $itemName => $item) {
			$propertyList = $item->getPropertyList();
			foreach ($propertyList as $propertyName => $property) {
				if (
					$property instanceof OneToOneProperty
					&& $property->getRelatedClass()
					&& $property->getRelatedClass() == $class
				) {
					$deleting = $property->getDeleting();
					$childItemList[$deleting][$itemName][$propertyName] = $property;
				}
			}
		}

		return $childItemList;
	}

	public function getHref()
	{
		return null;
	}

	public function getBrowseUrl()
	{
		return \URL::route(
			'admin.browse',
			array('class' => $this->getClass(), 'id' => $this->id)
		);
	}

	public function getTrashUrl()
	{
		return \URL::route(
			'admin.trash',
			array('class' => $this->getClass(), 'id' => $this->id)
		);
	}

	public function getDeleteUrl()
	{
		return \URL::route(
			'admin.delete',
			array('class' => $this->getClass(), 'id' => $this->id)
		);
	}

	public function getRestoreUrl()
	{
		return \URL::route(
			'admin.restore',
			array('class' => $this->getClass(), 'id' => $this->id)
		);
	}

	public function getEditUrl()
	{
		return \URL::route(
			'admin.edit',
			array('class' => $this->getClass(), 'id' => $this->id)
		);
	}
}