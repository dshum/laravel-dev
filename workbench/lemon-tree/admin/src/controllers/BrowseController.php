<?php namespace LemonTree;

class BrowseController extends BaseController {

	public function getAddTab(Element $currentElement)
	{
		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasViewAccess($currentElement)) {
			return \Redirect::route('admin');
		}

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
				$scope['error'] =
					'Невозможно удалить следующие элементы, '
					.'пока существуют связанные с ними элементы: ';
				foreach ($scope['restricted'] as $k => $element) {
					$item = $element->getItem();
					$scope['error'] .=
						($k ? ', ' : '')
						.'<a href="'.$element->getBrowseUrl().'">'
						.$element->{$item->getMainProperty()}
						.'</a>';
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
			$element = Element::getOnlyTrashedByClassId($classId);
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

	public function postList()
	{
		$isTrash = \Route::currentRouteName() == 'admin.trash.list';

		$loggedUser = \Sentry::getUser();

		$open = \Input::get('open');
		$expand = \Input::get('expand', false);
		$classId = \Input::get('classId');
		$class = \Input::get('item');
		$page = \Input::get('page');

		$site = \App::make('site');

		$item = $site->getItemByName($class);

		if ( ! $item instanceof Item) return null;

		$lists = $loggedUser->getParameter('lists');
		$pages = $loggedUser->getParameter('pages');

		$elementListView = null;

		if ($open == 'open') {
			$lists[$classId][$class] = true;
		} elseif ($open == 'false') {
			$lists[$classId][$class] = true;
		} elseif ($open == 'true') {
			$lists[$classId][$class] = false;
		}

		if ((int)$page > 1) {
			$pages[$classId][$class] = (int)$page;
		} elseif($page !== null) {
			unset($pages[$classId][$class]);
		}

		$loggedUser->
		setParameter('lists', $lists)->
		setParameter('pages', $pages);

		if ($open == 'open' || $expand) {
			$element = Element::getWithTrashedByClassId($classId);
			$elementListView = $this->getElementListView(
				$item, $element, $isTrash, true, $expand
			);
		}

		return $elementListView;
	}

	public function getIndex($currentElement = null)
	{
		$scope = array();

		$isTrash = \Route::currentRouteName() == 'admin.trash';

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
			$scope['currentTitle'] = $isTrash ? 'Корзина' : 'Lemon Tree';
			$scope['currentTabTitle'] = $isTrash ? 'Корзина' : 'Lemon Tree';
		}

		$scope = CommonFilter::apply($scope);

		$itemList = $site->getItemList();

		$bindItemList = array();

		if ($isTrash) {

			$parentList = array();

		} else {

			$parentList = $currentElement
				? $currentElement->getParentList()
				: array();

			$binds = $site->getBinds();

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

		}

		$elementListViewList = array();
		$open = true;

		foreach ($itemList as $itemName => $item) {

			$elementListView = $this->getElementListView(
				$item, $currentElement, $isTrash, $open
			);

			if ($elementListView) {
				$elementListViewList[$itemName] = $elementListView;
				$open = false;
			}

		}

		$scope['isTrash'] = $isTrash;
		$scope['parentList'] = $parentList;
		$scope['bindItemList'] = $bindItemList;
		$scope['elementListViewList'] = $elementListViewList;

		return \View::make('admin::browse', $scope);
	}

	private function getElementListView(
		Item $item,
		$currentElement = null,
		$isTrash = false,
		$defaultOpen = false,
		$expand = true
	)
	{
		$loggedUser = \Sentry::getUser();

		$classId = $currentElement
			? $currentElement->getClassId()
			: ($isTrash ? Site::TRASH : Site::ROOT);

		$parameters = array(
			'classId' => $classId,
			'item' => $item->getName(),
			'expand' => true,
		);

		$propertyList = $item->getPropertyList();

		if ( ! $currentElement && ! $item->getRoot() && ! $isTrash) {
			return null;
		}

		if ($currentElement) {
			$flag = false;
			foreach ($propertyList as $propertyName => $property) {
				if (
					$currentElement
					&& $property instanceof OneToOneProperty
					&& $property->getRelatedClass() == $currentElement->getClass()
				) $flag = true;
			}
			if ( ! $flag) {
				return null;
			}
		}

		$itemPropertyList = array();

		foreach ($propertyList as $propertyName => $property) {
			if (
				! $property->getShow()
				|| $property->getHidden()
			) continue;
			$itemPropertyList[$propertyName] = $property;
		}

		if ($isTrash) {

			$elementListCriteria = $item->getClass()->onlyTrashed()->where(
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
						}
					}
				}
			);

		} else {

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

		}

		$elementListCriteria->
		cacheTags($item->getName())->
		rememberForever();

		$total = $elementListCriteria->count();

		if ( ! $total) {
			return null;
		}

		$lists = $loggedUser->getParameter('lists');
		$pages = $loggedUser->getParameter('pages');

		$open = isset($lists[$classId][$item->getName()])
			? $lists[$classId][$item->getName()]
			: $defaultOpen;

		$page = isset($pages[$classId][$item->getName()])
			? $pages[$classId][$item->getName()]
			: null;

		if ($open || $expand) {

			$orderByList = $item->getOrderByList();

			foreach ($orderByList as $field => $direction) {
				$elementListCriteria->orderBy($field, $direction);
			}

			$perPage = $item->getPerPage();

			if ($perPage) {
//				\Paginator::setCurrentPage($page);
				$elementList = $elementListCriteria->paginate($perPage);
				if ($isTrash) {
					$elementList->setBaseUrl('/admin/trash/list');
				} else {
					$elementList->setBaseUrl('/admin/browse/list');
				}
				$elementList->appends($parameters);
			} else {
				$elementList = $elementListCriteria->get();
			}

		} else {

			$elementList = array();

		}

		$hideList = ! $expand;

		$scope['isTrash'] = $isTrash;
		$scope['currentElement'] = $currentElement;
		$scope['item'] = $item;
		$scope['itemPropertyList'] = $itemPropertyList;
		$scope['open'] = $open;
		$scope['total'] = $total;
		$scope['elementList'] = $elementList;
		$scope['hideList'] = $hideList;

		return \View::make('admin::list', $scope);
	}

}