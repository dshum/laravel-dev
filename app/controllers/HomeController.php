<?php

class HomeController extends BaseController {

	public function getSpecial()
	{
		$scope = array();

		View::share('currentElement', null);

		$scope = CommonFilter::apply($scope);

		$goodList =
			Good::where('special', true)->
			orderBy('name')->
			get();

		$scope['goodList'] = $goodList;

		return View::make('catalogue.special', $scope);
	}

	public function getNovelty()
	{
		$scope = array();

		View::share('currentElement', null);

		$scope = CommonFilter::apply($scope);

		$goodList =
			Good::where('novelty', true)->
			orderBy('name')->
			get();

		$scope['goodList'] = $goodList;

		return View::make('catalogue.novelty', $scope);
	}

	public function getIndex()
	{
		$scope = array();

		View::share('currentElement', null);

		$scope = CommonFilter::apply($scope);

		return View::make('home', $scope);
	}

}
