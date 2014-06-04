<?php

class CommonController extends BaseController {

	public function getIndex(Section $currentElement)
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		return View::make('common', $scope);
	}

}