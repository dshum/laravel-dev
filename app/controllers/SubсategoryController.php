<?php

class SubcategoryController extends BaseController {

	public function getIndex(Category $currentCategory, Subcategory $currentElement)
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		View::share('currentCategory', $currentCategory);
		View::share('currentElement', $currentElement);

		$subcategoryList =
			Subcategory::where('category_id', $currentCategory->id)->
			orderBy('order')->
			get();

		$goodList =
			Good::where('subcategory_id', $currentElement->id)->
			orderBy('order')->
			get();

		$scope['subcategoryList'] = $subcategoryList;
		$scope['goodList'] = $goodList;

		return View::make('catalogue.subcategory', $scope);
	}

}