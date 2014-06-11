<?php namespace LemonTree;

class MainController extends BaseController {

	public function getIndex($currentElement = null)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['login'] = null;
			return \View::make('admin::login', $scope);
		}

		$loggedUser = \Sentry::getUser();

		\View::share('currentElement', $currentElement);
		\View::share('loggedUser', $loggedUser);

		$site = \App::make('site');

		if ($currentElement) {
			$currentItem = $site->getItemByName($currentElement->getClass());
			$mainProperty = $currentItem->getMainProperty();
			$scope['currentTitle'] = $currentElement->$mainProperty;
			$scope['currentTabTitle'] = $currentElement->$mainProperty;
		} else {
			$scope['currentTitle'] = 'Lemon Tree';
			$scope['currentTabTitle'] = 'Lemon Tree';
		}

		$scope = CommonFilter::apply($scope);

		$parentList = $currentElement
			? $currentElement->getParentList()
			: array();

		$itemList = $site->getItemList();
		$binds = $site->getBinds();

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

		$itemPropertyList = array();
		$itemElementList = array();

		foreach ($itemList as $itemName => $item) {

			$propertyList = $item->getPropertyList();

			if ( ! $currentElement && ! $item->getRoot()) {
				unset($itemList[$itemName]);
				continue;
			} elseif ($currentElement) {
				$flag = false;
				foreach ($propertyList as $propertyName => $property) {
					if (
						$currentElement
						&& $property instanceof OneToOneProperty
						&& $property->getRelatedClass() == $currentElement->getClass()
					) $flag = true;
				}
				if (! $flag) {
					unset($itemList[$itemName]);
					continue;
				}
			}

			foreach ($propertyList as $propertyName => $property) {
				if (
					! $property->getShow()
					|| $property->getHidden()
				) continue;
				$itemPropertyList[$itemName][$propertyName] = $property;
			}

			$elementListCriteria = $item->getClass()->where(
				function($query) use ($propertyList, $currentElement) {
					if ($currentElement) {
						$query->orWhere('id', null);
					}
					foreach ($propertyList as $propertyName => $property) {
						if (
							$currentElement
							&& $property instanceof OneToOneProperty
							&& $property->getRelatedClass() == $currentElement->getClass()
						) {
							$query->orWhere(
								$property->getName(), $currentElement->id
							);
						} elseif (
							! $currentElement
							&& $property instanceof OneToOneProperty
						) {
							$query->orWhere(
								$property->getName(), null
							);
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

			if ($item->getPerPage()) {
				$elementList = $elementListCriteria->paginate($item->getPerPage());
			} else {
				$elementList = $elementListCriteria->get();
			}

			if (sizeof ($elementList)) {
				$itemElementList[$itemName] = $elementList;
			} else {
				unset($itemList[$itemName]);
			}

		}

		$scope['parentList'] = $parentList;
		$scope['bindItemList'] = $bindItemList;
		$scope['itemList'] = $itemList;
		$scope['itemPropertyList'] = $itemPropertyList;
		$scope['itemElementList'] = $itemElementList;
		$scope['route'] = 'admin.browse';

		return \View::make('admin::main', $scope);
	}

}