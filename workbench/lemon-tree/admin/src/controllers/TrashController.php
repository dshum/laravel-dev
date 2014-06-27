<?php namespace LemonTree;

class TrashController extends BaseController {

	const PER_PAGE = 10;

	public function getIndex($currentElement = null)
	{
		$scope = array();

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
			$scope['currentTitle'] = 'Корзина';
			$scope['currentTabTitle'] = 'Корзина';
		}

		$scope = CommonFilter::apply($scope);

		$parentList = $currentElement
			? $currentElement->getParentList()
			: array();

		$itemList = $site->getItemList();

		$itemPropertyList = array();
		$itemElementList = array();

		foreach ($itemList as $itemName => $item) {

			$propertyList = $item->getPropertyList();

			foreach ($propertyList as $propertyName => $property) {
				if (
					! $property->getShow()
					|| $property->getHidden()
				) continue;
				$itemPropertyList[$itemName][$propertyName] = $property;
			}

			$elementListCriteria = $item->getClass()->onlyTrashed()->where(
				function($query) use ($propertyList, $currentElement) {
					foreach ($propertyList as $propertyName => $property) {
						if (
							$currentElement
							&& $property instanceof OneToOneProperty
							&& $property->getRelatedClass() == $currentElement->getClass()
						) {
							$query->orWhere(
								$property->getName(), $currentElement->id
							);
						}
					}
				}
			);

			$elementListCriteria->
			cacheTags($itemName)->
			rememberForever();

			$elementList = $elementListCriteria->paginate(static::PER_PAGE);

			if (sizeof ($elementList)) {
				$itemElementList[$itemName] = $elementList;
			} else {
				unset($itemList[$itemName]);
			}

		}

		$scope['parentList'] = $parentList;
		$scope['itemList'] = $itemList;
		$scope['itemPropertyList'] = $itemPropertyList;
		$scope['itemElementList'] = $itemElementList;
		$scope['route'] = 'admin.trash';

		return \View::make('admin::trash', $scope);
	}

}