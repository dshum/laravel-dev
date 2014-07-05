<?php namespace LemonTree;

class TreeController extends BaseController {

	public function check(Element $element)
	{
		$total = 0;

		$site = \App::make('site');

		$itemList = $site->getItemList();
		$binds = $site->getBindsTree();

		$bindItemList = array();

		foreach ($itemList as $itemName => $item) {
			if (isset($binds[$element->getClass()][$itemName])) {
				$bindItemList[$itemName] = $item;
			}
			if (isset($binds[$element->getClassId()][$itemName])) {
				$bindItemList[$itemName] = $item;
			}
		}

		if ( ! $bindItemList) return $total;

		$itemElementList = array();

		foreach ($bindItemList as $itemName => $item) {

			$propertyList = $item->getPropertyList();

			$elementListCriteria = $item->getClass()->where(
				function($query) use ($propertyList, $element) {
					foreach ($propertyList as $propertyName => $property) {
						if (
							$property instanceof OneToOneProperty
							&& $property->getRelatedClass() == $element->getClass()
						) {
							$query->orWhere(
								$property->getName(), $element->id
							);
						}
					}
				}
			);

			$elementListCriteria->
			cacheTags($itemName)->
			rememberForever();

			$count = $elementListCriteria->count();

			$total += $count;

		}

		return $total;
	}

	public function check1(OneToOneProperty $currentProperty, Element $element)
	{
		$total = 0;

		$site = \App::make('site');

		$itemList = $site->getItemList();
		$binds = $currentProperty->getBinds();

		$bindItemList = array();

		foreach ($itemList as $itemName => $item) {
			if (isset($binds[$element->getClass()][$itemName])) {
				$bindItemList[$itemName] = $item;
			}
			if (isset($binds[$element->getClassId()][$itemName])) {
				$bindItemList[$itemName] = $item;
			}
		}

		if ( ! $bindItemList) return $total;

		$itemElementList = array();

		foreach ($bindItemList as $itemName => $item) {

			$propertyList = $item->getPropertyList();

			$elementListCriteria = $item->getClass()->where(
				function($query) use ($propertyList, $element) {
					foreach ($propertyList as $propertyName => $property) {
						if (
							$property instanceof OneToOneProperty
							&& $property->getRelatedClass() == $element->getClass()
						) {
							$query->orWhere(
								$property->getName(), $element->id
							);
						}
					}
				}
			);

			$elementListCriteria->
			cacheTags($itemName)->
			rememberForever();

			$count = $elementListCriteria->count();

			$total += $count;

		}

		return $total;
	}

	public function postOpen()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$tree = $loggedUser->getParameter('tree');

		$classId = \Input::get('classId');
		$open = \Input::get('open');

		$treeView = null;

		if ($open == 'open') {
			$element = Element::getByClassId($classId);
			if ($element instanceof Element) {
				$treeView = \App::make('LemonTree\TreeController')->show($element);
			}
			$tree[$classId] = 1;
		} elseif ($open == 'false') {
			$tree[$classId] = 1;
		} else {
			if (isset($tree[$classId])) {
				unset($tree[$classId]);
			}
		}

		$loggedUser->setParameter('tree', $tree);

		return $treeView;
	}

	public function postOpen1()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$itemName = \Input::get('itemName');
		$propertyName = \Input::get('propertyName');
		$classId = \Input::get('classId');

		$site = \App::make('site');

		$item = $site->getItemByName($itemName);
		$property = $item ? $item->getPropertyByName($propertyName) : null;
		$element = Element::getByClassId($classId);

		if ( ! $item || ! $property || ! $element) return null;

		$treeView = null;

		if ($element instanceof Element) {
			$treeView = \App::make('LemonTree\TreeController')->show1($property, $element);
		}

		return $treeView;
	}

	public function show($currentElement = null)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$tree = $loggedUser->getParameter('tree');

		$site = \App::make('site');

		$itemList = $site->getItemList();
		$binds = $site->getBindsTree();

		$bindItemList = array();

		foreach ($itemList as $itemName => $item) {
			if ($currentElement) {
				if (isset($binds[$currentElement->getClass()][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
				if (isset($binds[$currentElement->getClassId()][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
			} else {
				if (isset($binds[Site::ROOT][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
			}
		}

		if ( ! $bindItemList) return null;

		$itemElementList = array();
		$treeView = array();
		$treeCount = array();

		foreach ($bindItemList as $itemName => $item) {

			$propertyList = $item->getPropertyList();

			$elementListCriteria = $item->getClass()->where(
				function($query) use ($propertyList, $currentElement) {
					if ($currentElement) {
						foreach ($propertyList as $propertyName => $property) {
							if (
								$property instanceof OneToOneProperty
								&& $property->getRelatedClass() == $currentElement->getClass()
							) {
								$query->orWhere(
									$property->getName(), $currentElement->id
								);
							}
						}
					} else {
						foreach ($propertyList as $propertyName => $property) {
							if ($property instanceof OneToOneProperty) {
								$query->orWhere(
									$property->getName(), null
								);
							}
						}
					}
				}
			);

			$orderByList = $item->getOrderByList();

			foreach ($orderByList as $field => $direction) {
				$elementListCriteria->orderBy($field, $direction);
			}

			$elementListCriteria->
			cacheTags($itemName)->
			rememberForever();

			$elementList = $elementListCriteria->get();

			if (sizeof ($elementList)) {
				$itemElementList[$itemName] = $elementList;
			} else {
				unset($bindItemList[$itemName]);
			}

			foreach ($elementList as $element) {
				if (isset($tree[$element->getClassId()])) {
					$view = \App::make('LemonTree\TreeController')->show($element);
					if ($view) {
						$treeView[$element->getClassId()] = $view;
					}
				} else {
					$total = $this->check($element);
					$treeCount[$element->getClassId()] = $total;
				}
			}

		}

		if ( ! $itemElementList) return null;

		$scope['currentElement'] = $currentElement;
		$scope['treeItemList'] = $bindItemList;
		$scope['treeItemElementList'] = $itemElementList;
		$scope['treeView'] = $treeView;
		$scope['treeCount'] = $treeCount;
		$scope['tree'] = $tree;

		return \View::make('admin::tree', $scope);
	}

	public function show1(OneToOneProperty $currentProperty, $currentElement = null)
	{
		$scope = array();

		$site = \App::make('site');

		$itemList = $site->getItemList();
		$binds = $currentProperty->getBinds();
		$value = $currentProperty->getValue();
		$parentList = $value ? $value->getParentList() : array();

		$parents = array();

		foreach ($parentList as $parent) {
			$parents[$parent->getClassId()] = 1;
		}

		$bindItemList = array();

		foreach ($itemList as $itemName => $item) {
			if ($currentElement) {
				if (isset($binds[$currentElement->getClass()][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
				if (isset($binds[$currentElement->getClassId()][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
			} else {
				if (isset($binds[null][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
				if (isset($binds[Site::ROOT][$itemName])) {
					$bindItemList[$itemName] = $item;
				}
			}
		}

		if ( ! $bindItemList) return null;

		$itemElementList = array();
		$treeView = array();
		$treeCount = array();

		foreach ($bindItemList as $itemName => $item) {

			if (isset($binds[null][$itemName])) {

				$elementListCriteria = $item->getClass()->query();

			} else {

				$propertyList = $item->getPropertyList();

				$elementListCriteria = $item->getClass()->where(
					function($query) use ($propertyList, $currentElement) {
						if ($currentElement) {
							foreach ($propertyList as $propertyName => $property) {
								if (
									$property instanceof OneToOneProperty
									&& $property->getRelatedClass() == $currentElement->getClass()
								) {
									$query->orWhere(
										$property->getName(), $currentElement->id
									);
								}
							}
						} else {
							foreach ($propertyList as $propertyName => $property) {
								if ($property instanceof OneToOneProperty) {
									$query->orWhere(
										$property->getName(), null
									);
								}
							}
						}
					}
				);

			}

			$orderByList = $item->getOrderByList();

			foreach ($orderByList as $field => $direction) {
				$elementListCriteria->orderBy($field, $direction);
			}

			$elementListCriteria->
			cacheTags($itemName)->
			rememberForever();

			$elementList = $elementListCriteria->get();

			if (sizeof ($elementList)) {
				$itemElementList[$itemName] = $elementList;
			} else {
				unset($bindItemList[$itemName]);
			}

			foreach ($elementList as $element) {
				if (isset($parents[$element->getClassId()])) {
					$view = \App::make('LemonTree\TreeController')->show1($currentProperty, $element);
					if ($view) {
						$treeView[$element->getClassId()] = $view;
					}
				} else {
					$total = $this->check1($currentProperty, $element);
					$treeCount[$element->getClassId()] = $total;
				}
			}

		}

		if ( ! $itemElementList) return null;

		$scope['treeItemList'] = $bindItemList;
		$scope['treeItemElementList'] = $itemElementList;
		$scope['treeView'] = $treeView;
		$scope['treeCount'] = $treeCount;
		$scope['currentProperty'] = $currentProperty;
		$scope['value'] = $value;
		$scope['parents'] = $parents;

		return \View::make('admin::properties.OneToOneProperty.tree', $scope);
	}

}