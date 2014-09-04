<?php

class GoodFilter extends BaseController {

	public function getIndex(LemonTree\Item $item, &$criteria)
	{
		$scope = array();

		View::share('currentItem', $item);

		$criteria->getQuery()->orders = null;
		$criteria->orderBy('absent', 'asc')->orderBy('name');

		return View::make('plugins.goodFilter', $scope);
	}

}
