<?php

class CommonFilter {

	public static function apply($scope = array()) {

		$categoryList =
			Category::orderBy('order')->
			cacheTags('Category')->rememberForever()->
			get();

		$scope['categoryList'] = $categoryList;

		return $scope;
	}

}
