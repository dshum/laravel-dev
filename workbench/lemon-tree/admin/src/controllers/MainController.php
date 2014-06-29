<?php namespace LemonTree;

class MainController extends BaseController {

	public function getAddTab(Element $currentElement)
	{
		$loggedUser = \Sentry::getUser();
		
		$tabs = $loggedUser->tabs;

		foreach ($tabs as $tab) {
			if ($tab->is_active) {
				$tab->is_active = false;
				$tab->save();
			}
		}
		
		$site = \App::make('site');
		$currentItem = $site->getItemByName($currentElement->getClass());
		$mainProperty = $currentItem->getMainProperty();

		$tab = new Tab;
		$tab->user_id = $loggedUser->id;
		$tab->title = $currentElement->$mainProperty;
		$tab->url = $currentElement->getBrowseUrl();
		$tab->is_active = true;
		$tab->show_tree = false;
		$tab->save();
		
		return \Redirect::to($currentElement->getBrowseUrl());
	}
	
	public function postDelete()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$check = \Input::get('check');

		if ( ! $check) {
			return json_encode($scope);
		}

		$elementList = array();

		foreach ($check as $classId) {
			$element = Element::getByClassId($classId);
			if ($element) {
				$elementList[] = $element;
			}
		}

		if ( ! $elementList) {
			return json_encode($scope);
		}

		$scope['restricted'] = array();

		try {
			foreach ($elementList as $element) {
				if ( ! $element->delete()) {
					$scope['restricted'][] = $element;
				}
			}
			if ($scope['restricted']) {
				$scope['error'] = 'Невозможно удалить следующие элементы, пока существуют связанные с ними элементы: ';
				foreach ($scope['restricted'] as $k => $element) {
					$item = $element->getItem();
					$scope['error'] .= ($k ? ', ' : '').'<a href="'.$element->getBrowseUrl().'">'.$element->{$item->getMainProperty()}.'</a>';
				}
			} else {
				$scope['status'] = 'ok';
			}
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage().PHP_EOL.$e->getTraceAsString();
		}

		return json_encode($scope);
	}

	public function postForceDelete()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		try {
			$currentElement->forceDelete();
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage().PHP_EOL.$e->getTraceAsString();
		}

		return json_encode($scope);
	}

	public function postRestore()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$check = \Input::get('check');

		if ( ! $check) {
			return json_encode($scope);
		}

		$elementList = array();

		foreach ($check as $classId) {
			$element = Element::getByClassId($classId, true);
			if ($element) {
				$elementList[] = $element;
			}
		}

		if ( ! $elementList) {
			return json_encode($scope);
		}

		try {
			foreach ($elementList as $element) {
				$element->restore();
			}
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getIndex($currentElement = null)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();
		
		if (
			$currentElement 
			&& ! $loggedUser->hasViewAccess($currentElement)
		) {
			return \Redirect::route('admin');
		}

		\View::share('currentElement', $currentElement);

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
				if ( ! $flag) {
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