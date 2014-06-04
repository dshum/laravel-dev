<?php

class FirstpageController extends BaseController {

	public function getIndex()
	{
		$scope = array();

		View::share('currentElement', null);

		$scope = CommonFilter::apply($scope);

		return View::make('firstpage', $scope);
	}

}
