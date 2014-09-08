<?php

class CommonFilter {

	public static function apply($scope = array()) {

		$scope = LoginFilter::apply($scope);

		View::share('currentElement', null);

		$currentRouteName = Route::currentRouteName();

		$categoryList =
			Category::orderBy('order')->
			get();

		$scope['currentRouteName'] = $currentRouteName;
		$scope['categoryList'] = $categoryList;

		return $scope;
	}

}
