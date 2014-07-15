<?php namespace LemonTree;

class SearchController extends BaseController {

	public function postItem()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		$class = \Input::get('item');

		$currentItem = $class ? $site->getItemByName($class) : null;

		if ( ! $currentItem) return null;

		$propertyList = $currentItem->getPropertyList();

		$scope['currentItem'] = $currentItem;
		$scope['propertyList'] = $propertyList;

		return \View::make('admin::searchItem', $scope);
	}

	public function postList()
	{
		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		$class = \Input::get('item');
		$orderDefault = \Input::get('orderDefault');
		$orderField = \Input::get('orderField');
		$orderDirection = \Input::get('orderDirection', 'asc');
		$page = \Input::get('page');

		$currentItem = $class ? $site->getItemByName($class) : null;

		if ( ! $currentItem) return null;

		$orders = $loggedUser->getParameter('orders');
		$pages = $loggedUser->getParameter('pages');

		$classId = Site::SEARCH;

		if ($orderDefault) {
			if (isset($orders[$classId][$class])) {
				unset($orders[$classId][$class]);
			}
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
		}

		if ((int)$page > 1) {
			$pages[Site::SEARCH][$class] = (int)$page;
		} elseif($page !== null) {
			unset($pages[Site::SEARCH][$class]);
		}

		$loggedUser->
		setParameter('orders', $orders)->
		setParameter('pages', $pages);

		$propertyList = $currentItem->getPropertyList();

		$elementListView = $this->getElementListView($currentItem);

		$scope['currentItem'] = $currentItem;
		$scope['propertyList'] = $propertyList;
		$scope['elementListView'] = $elementListView;

		return $elementListView;
	}

	public function getIndex()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		$scope = CommonFilter::apply($scope);

		$itemList = $site->getItemList();

		$class = \Input::get('item');

		$currentItem = $class ? $site->getItemByName($class) : null;

		$propertyList = $currentItem
			? $currentItem->getPropertyList() : array();

		$id = \Input::get('id');

		$elementListView = $currentItem
			? $this->getElementListView($currentItem)
			: null;

		$scope['itemList'] = $itemList;
		$scope['currentItem'] = $currentItem;
		$scope['propertyList'] = $propertyList;
		$scope['id'] = $id;
		$scope['elementListView'] = $elementListView;

		return \View::make('admin::search', $scope);
	}

	private function getElementListView(Item $item)
	{
		$loggedUser = \Sentry::getUser();

		$parameters = array(
			'item' => $item->getName(),
			'expand' => true,
		);

		$listBaseUrl = '/admin/search/list';
		$listBaseRoute = 'admin.search.list';

		$propertyList = $item->getPropertyList();

		$itemPropertyList = array();

		foreach ($propertyList as $propertyName => $property) {
			if (
				! $property->getShow()
				|| $property->getHidden()
			) continue;
			$itemPropertyList[$propertyName] = $property;
		}

		$elementListCriteria = $item->getClass()->where(
			function($query) use ($propertyList) {
				foreach ($propertyList as $propertyName => $property) {

				}
			}
		);

		$elementListCriteria->
		cacheTags($item->getName())->
		rememberForever();

		$total = $elementListCriteria->count();

		if ( ! $total) {
			return null;
		}

		$orders = $loggedUser->getParameter('orders');
		$pages = $loggedUser->getParameter('pages');

		$classId = Site::SEARCH;

		$orderBy = isset($orders[$classId][$item->getName()])
			? $orders[$classId][$item->getName()]
			: null;

		$page = isset($pages[Site::SEARCH][$item->getName()])
			? $pages[Site::SEARCH][$item->getName()]
			: null;

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

		$scope['isSearch'] = true;
		$scope['currentElement'] = null;
		$scope['classId'] = $classId;
		$scope['item'] = $item;
		$scope['currentOrderByList'] = $currentOrderByList;
		$scope['defaultOrderBy'] = $defaultOrderBy;
		$scope['itemPropertyList'] = $itemPropertyList;
		$scope['open'] = true;
		$scope['total'] = $total;
		$scope['elementList'] = $elementList;
		$scope['listBaseUrl'] = $listBaseUrl;
		$scope['listBaseRoute'] = $listBaseRoute;

		return \View::make('admin::list', $scope);
	}

}