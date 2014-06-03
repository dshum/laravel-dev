<?php namespace LemonTree;

class TrashController extends BaseController {

	const PER_PAGE = 10;

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

		$scope = TreeFilter::apply($scope);

		$parentList = $currentElement
			? $currentElement->getParentList()
			: array();

		$site = \App::make('site');

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