<?php

class MoneyStat2 extends BaseController {

	public function postSend2(LemonTree\ElementInterface $element)
	{
		$scope['hi'] = 'Welcome!';

		return json_encode($scope);
	}

	public function postSend(LemonTree\ElementInterface $element)
	{
		return Redirect::to($element->getEditUrl());
	}

	public function getIndex(LemonTree\ElementInterface $element)
	{
		$scope = array();

		View::share('currentElement', $element);

		return View::make('plugins.moneyStat2', $scope);
	}

}
