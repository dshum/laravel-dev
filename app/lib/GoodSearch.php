<?php

class GoodSearch extends BaseController {

	public function postSend(LemonTree\Item $item)
	{
		$scope['hi'] = 'Welcome!';

		return json_encode($scope);
	}

	public function getIndex(LemonTree\Item $item)
	{
		$scope = array();

		View::share('currentItem', $item);

		return View::make('plugins.goodSearch', $scope);
	}

}
