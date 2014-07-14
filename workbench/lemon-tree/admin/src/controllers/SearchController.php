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
		$page = \Input::get('page');

		$currentItem = $class ? $site->getItemByName($class) : null;

		if ( ! $currentItem) return null;

		$pages = $loggedUser->getParameter('pages');

		if ((int)$page > 1) {
			$pages[Site::SEARCH][$class] = (int)$page;
		} elseif($page !== null) {
			unset($pages[Site::SEARCH][$class]);
		}

		$loggedUser->
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

		$parameters = array('item' => $item->getName());

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

		$pages = $loggedUser->getParameter('pages');

		$page = isset($pages[Site::SEARCH][$item->getName()])
			? $pages[Site::SEARCH][$item->getName()]
			: null;

		$orderByList = $item->getOrderByList();

		foreach ($orderByList as $field => $direction) {
			$elementListCriteria->orderBy($field, $direction);
		}

		$perPage = $item->getPerPage();

		if ($perPage) {
			\Paginator::setCurrentPage($page);
			$elementList = $elementListCriteria->paginate($perPage);
			$elementList->setBaseUrl('/admin/search/list');
			$elementList->appends($parameters);
		} else {
			$elementList = $elementListCriteria->get();
		}

		$scope['isSearch'] = true;
		$scope['currentElement'] = null;
		$scope['item'] = $item;
		$scope['itemPropertyList'] = $itemPropertyList;
		$scope['open'] = true;
		$scope['total'] = $total;
		$scope['elementList'] = $elementList;

		return \View::make('admin::list', $scope);
	}

}