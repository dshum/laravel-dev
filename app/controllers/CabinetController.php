<?php

class CabinetController extends BaseController {

	public function getIndex()
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		return View::make('user.cabinet', $scope);
	}

}
