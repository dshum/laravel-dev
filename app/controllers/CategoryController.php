<?php

class CategoryController extends BaseController {

	public function getIndex(Category $currentElement)
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		View::share('currentElement', $currentElement);

		$subcategoryList =
			Subcategory::where('category_id', $currentElement->id)->
			orderBy('order')->
			get();

		$goodList =
			Good::where('category_id', $currentElement->id)->
			orderBy('order')->
			get();

		$scope['subcategoryList'] = $subcategoryList;
		$scope['goodList'] = $goodList;

		return View::make('catalogue.category', $scope);
	}

}