<?php

class CategoryController extends BaseController {

	public function getIndex(Category $currentElement)
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		View::share('currentElement', $currentElement);

		$goodList =
			Good::where('category_id', $currentElement->id)->
			orderBy('order')->
			cacheTags('Category')->rememberForever()->
			get();

		$scope['goodList'] = $goodList;

		return View::make('catalogue.category', $scope);
	}

}