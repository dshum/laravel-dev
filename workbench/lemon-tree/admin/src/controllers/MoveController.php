<?php namespace LemonTree;

class MoveController extends BaseController {

	public function postMove()
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['login'] = null;
			return \View::make('admin::login', $scope);
		}

		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		$input = \Input::all();

		$itemName = \Input::get('item');
		$checks = \Input::get('check');
		$redirect = \Input::get('redirect');

		if ( ! $itemName || ! $checks) {
			return \Redirect::route('admin');
		}

		$item = $site->getItemByName($itemName);

		$elementList = array();
		$onePropertyList = array();

		foreach ($checks as $id) {
			$element = $itemName::find($id);
			if ($element) {
				$elementList[] = $element;
			}
		}

		if ( ! $elementList) {
			return \Redirect::route('admin');
		}

		$propertyList = $item->getPropertyList();

		foreach ($propertyList as $propertyName => $property) {
			if (
				! $property instanceof OneToOneProperty
				|| $property->getReadonly()
				|| $property->getHidden()
				|| ! isset($input[$propertyName])
				|| $input[$propertyName] == -1
			) continue;
			$onePropertyList[$property->getName()] = $property;
		}

		if ( ! $onePropertyList) {
			return \Redirect::route('admin');
		}

		foreach ($elementList as $element) {
			foreach ($onePropertyList as $propertyName => $property) {
				$property->setElement($element)->set();
			}
			$element->save();
		}

		return $redirect
			? \Redirect::to($redirect)
			: \Redirect::route('admin');
	}

	public function postMoving()
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['login'] = null;
			return \View::make('admin::login', $scope);
		}

		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		\View::share('loggedUser', $loggedUser);

		$scope['currentTitle'] = 'Перемещение элемента';
		$scope['currentTabTitle'] = 'Перемещение элемента';

		$scope = CommonFilter::apply($scope);

		$check = \Input::get('check');
		$redirect = \Input::get('redirect');

		if ( ! $check) {
			return \Redirect::back();
		}

		$elementList = array();
		$onePropertyList = array();

		foreach ($check as $classId) {
			$element = Element::getByClassId($classId);
			if ($element) {
				if ( ! isset($item)) $item = $element->getItem();
				$elementList[] = $element;
			}
		}

		if ( ! $elementList) {
			return \Redirect::back();
		}

		$propertyList = $item->getPropertyList();

		foreach ($propertyList as $property) {
			if (
				! $property instanceof OneToOneProperty
				|| $property->getReadonly()
				|| $property->getHidden()
			) continue;
			$onePropertyList[$property->getName()] = $property;
		}

		$scope['redirect'] = $redirect;
		$scope['elementList'] = $elementList;
		$scope['item'] = $item;
		$scope['onePropertyList'] = $onePropertyList;

		return \View::make('admin::move', $scope);
	}

}
