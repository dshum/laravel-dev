<?php

class CommonFilter {

	public static function apply($scope = array()) {

		$currentRouteName = Route::currentRouteName();

		$categoryList =
			Category::orderBy('order')->
			get();

		$scope['currentRouteName'] = $currentRouteName;
		$scope['categoryList'] = $categoryList;

		return $scope;
	}

}
