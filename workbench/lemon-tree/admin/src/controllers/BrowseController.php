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

			$dropped = array();

			foreach ($elementList as $element) {
				if ($element->delete()) {
					$dropped[] = $element->getClassId();
				} else {
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

			if (sizeof($dropped)) {
				UserAction::log(
					UserActionType::ACTION_TYPE_DROP_ELEMENT_LIST_TO_TRASH_ID,
					implode(', ', $dropped)
				);
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

			$dropped = array();

			foreach ($elementList as $element) {
				if ($element->forceDelete()) {
					$dropped[] = $element->getClassId();
				} else {
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

			if (sizeof($dropped)) {
				UserAction::log(
					UserActionType::ACTION_TYPE_DROP_ELEMENT_LIST_TO_TRASH_ID,
					implode(', ', $dropped)
				);
			}

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
			$restored = array();
			foreach ($elementList as $element) {
				$element->restore();
				$restored[] = $element->getClassId();
			}
			UserAction::log(
				UserActionType::ACTION_TYPE_RESTORE_ELEMENT_LIST_ID,
				implode(', ', $restored)
			);
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
		$orderDefault = \Input::get('orderDefault');
		$orderField = \Input::get('orderField');
		$orderDirection = \Input::get('orderDirection', 'asc');
		$page = \Input::get('page');

		$site = \App::make('site');

		$item = $site->getItemByName($class);

		if ( ! $item instanceof Item) return null;

		$orderByList = $item->getOrderByList();

		$lists = $loggedUser->getParameter('lists');
		$orders = $loggedUser->getParameter('orders');
		$pages = $loggedUser->getParameter('pages');

		$elementListView = null;

		if ($open == 'open') {
			$lists[$classId][$class] = true;
		} elseif ($open == 'false') {
			$lists[$classId][$class] = true;
		} elseif ($open == 'true') {
			$lists[$classId][$class] = false;
		}

		if ($orderDefault) {
			if (isset($orders[$classId][$class])) {
				unset($orders[$classId][$class]);
			}
			$page = 1;
		} elseif ($orderField && $orderDirection) {
			$orders[$classId][$class] = array(
				'field' => $orderField,
				'direction' => $orderDirection,
			);
			if (
				isset($orderByList[$orderField])
				&& $orderByList[$orderField] == $orderDirection
				&& sizeof($orderByList) < 2
			) {
				unset($orders[$classId][$class]);
			}
			$page = 1;
		}

		if ((int)$page > 1) {
			$pages[$classId][$class] = (int)$page;
		} elseif($page !== null) {
			unset($pages[$classId][$class]);
		}

		$loggedUser->
		setParameter('lists', $lists)->
		setParameter('orders', $orders)->
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

		$browsePluginView = null;

		if ($currentElement) {
			$browsePlugin = $site->getBrowsePlugin($currentElement->getClassId());

			if ($browsePlugin) {
				try {
					$view = \App::make($browsePlugin)->getIndex($currentElement);
					if ($view) {
						$browsePluginView = is_string($view)
							? $view : $view->render();
					}
				} catch (\Exception $e) {
					$browsePluginView = nl2br($e->getTraceAsString());
				}
			}
		}

		\View::share('browsePluginView', $browsePluginView);

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

		$listBaseUrl = $isTrash ? '/admin/trash/list' : '/admin/browse/list';
		$listBaseRoute = $isTrash ? 'admin.trash.list' : 'admin.browse.list';

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
		$orders = $loggedUser->getParameter('orders');
		$pages = $loggedUser->getParameter('pages');

		$open = isset($lists[$classId][$item->getName()])
			? $lists[$classId][$item->getName()]
			: $defaultOpen;

		$orderBy = isset($orders[$classId][$item->getName()])
			? $orders[$classId][$item->getName()]
			: null;

		$page = isset($pages[$classId][$item->getName()])
			? $pages[$classId][$item->getName()]
			: null;

		$browseFilterView = null;

		if ($open || $expand) {

			$orderByList = $item->getOrderByList();

			$currentOrderByList = array();

			if (
				isset($orderBy['field'])
				&& isset($orderBy['direction'])
				&& (
					! isset($orderByList[$orderBy['field']])
					|| $orderByList[$orderBy['field']] != $orderBy['direction']
					|| sizeof($orderByList) > 1
				)
			) {
				$elementListCriteria->orderBy(
					$orderBy['field'],
					$orderBy['direction']
				);
				$currentOrderByList[$orderBy['field']] = $orderBy['direction'];
				$defaultOrderBy = false;
			} else {
				foreach ($orderByList as $field => $direction) {
					$elementListCriteria->orderBy($field, $direction);
					$currentOrderByList[$field] = $direction;
				}
				$defaultOrderBy = true;
			}

			$site = \App::make('site');

			$browseFilter = $site->getBrowseFilter($item->getName());

			if ($browseFilter) {
				try {
					$view = \App::make($browseFilter)->getIndex($item, $elementListCriteria);
					if ($view) {
						$browseFilterView = is_string($view)
							? $view : $view->render();
					}
				} catch (\Exception $e) {
					$browseFilterView = nl2br($e->getMessage().PHP_EOL.$e->getTraceAsString());
				}
			}

			$perPage = $item->getPerPage();

			if ($perPage) {
				if ($page > ceil($total / $perPage)) {
					$page = ceil($total / $perPage);
				}
				\Paginator::setCurrentPage($page);
				$elementList = $elementListCriteria->paginate($perPage);
				$elementList->setBaseUrl($listBaseUrl);
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
		$scope['classId'] = $classId;
		$scope['item'] = $item;
		$scope['currentOrderByList'] = $currentOrderByList;
		$scope['defaultOrderBy'] = $defaultOrderBy;
		$scope['itemPropertyList'] = $itemPropertyList;
		$scope['open'] = $open;
		$scope['total'] = $total;
		$scope['elementList'] = $elementList;
		$scope['listBaseUrl'] = $listBaseUrl;
		$scope['listBaseRoute'] = $listBaseRoute;
		$scope['hideList'] = $hideList;
		$scope['browseFilterView'] = $browseFilterView;

		return \View::make('admin::list', $scope);
	}

}