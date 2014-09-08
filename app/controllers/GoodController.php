<?php

class GoodController extends BaseController {

	public function getIndex(Category $currentCategory, Good $currentElement)
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		View::share('currentCategory', $currentCategory);
		View::share('currentElement', $currentElement);

		return View::make('catalogue.good', $scope);
	}

}