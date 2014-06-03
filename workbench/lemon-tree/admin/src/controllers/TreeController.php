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

	public function postOpen()
	{
		$scope = array();

		$classId = \Input::get('classId');
		$open = \Input::get('open');

		$treeView = null;

		if ($open == 'open') {
			$element = Element::getByClassId($classId);
			if ($element instanceof Element) {
				$treeView = \App::make('LemonTree\TreeController')->show($element);
			}
			$tree = \Session::get('tree');
			$tree[$classId] = 1;
			\Session::put('tree', $tree);
		} elseif ($open == 'false') {
			$tree = \Session::get('tree');
			$tree[$classId] = 1;
			\Session::put('tree', $tree);
		} else {
			$tree = \Session::get('tree');
			if (isset($tree[$classId])) {
				unset($tree[$classId]);
			}
			\Session::put('tree', $tree);
		}

		return $treeView;
	}

	public function show($currentElement = null)
	{
		$scope = array();

		$tree = \Session::get('tree');

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

		$scope['treeItemList'] = $bindItemList;
		$scope['treeItemElementList'] = $itemElementList;
		$scope['treeView'] = $treeView;
		$scope['treeCount'] = $treeCount;
		$scope['tree'] = $tree;

		return \View::make('admin::tree', $scope);
	}

}