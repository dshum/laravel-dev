<?php namespace LemonTree;

class EditController extends BaseController {

	public function postDelete(Element $currentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		try {
			if ($currentElement->delete()) {
				$scope['status'] = 'ok';
			} else {
				$scope['error'] = 'Невозможно удалить этот элемент, пока существуют связанные с ним элементы.';
			}
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage().PHP_EOL.$e->getTraceAsString();
		}

		return json_encode($scope);
	}

	public function postForceDelete(Element $currentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		try {
			$currentElement->forceDelete();
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage().PHP_EOL.$e->getTraceAsString();
		}

		return json_encode($scope);
	}

	public function postRestore(Element $currentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		try {
			$currentElement->restore();
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function postAdd(Element $currentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		$input = \Input::all();

		$site = \App::make('site');

		$currentItem = $site->getItemByName($currentElement->getClass());

		$propertyList = $currentItem->getPropertyList();

		$rules = array();
		$messages = array();

		foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden() || $property->getReadonly()) continue;
			if ($property->getRequired()) {
				$rules[$propertyName][] = 'required';
				$messages[$propertyName.'.required'] = $propertyName;
			}
			if ($property->getRules()) {
				foreach ($property->getRules() as $rule) {
					$rules[$propertyName][] = $rule;
					if (strpos($rule, ':')) {
						list($name, $value) = explode(':', $rule, 2);
						$messages[$propertyName.'.'.$name] = $propertyName;
					}
				}
				$messages[$propertyName] = $propertyName;
			}

		}

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden() || $property->getReadonly()) continue;
			$property->setElement($currentElement)->set();
		}

		try {
			$maxOrder = $currentItem->getClass()->max('order');
			$currentElement->order = $maxOrder + 1;
		} catch (\Exception $e) {
			$currentElement->order = 1;
		}

		try {
			$currentElement->save();
			$scope['status'] = 'ok';
			$parentElement = $currentElement->getParent();
			if ($parentElement) {
				$scope['redirect'] = $parentElement->getBrowseUrl();
			} else {
				$scope['redirect'] = \URL::route('admin');
			}
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function postSave(Element $currentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		$input = \Input::all();

		$site = \App::make('site');

		$currentItem = $site->getItemByName($currentElement->getClass());

		$propertyList = $currentItem->getPropertyList();

		$rules = array();
		$messages = array();

		foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden() || $property->getReadonly()) continue;
			if ($property->getRequired()) {
				$rules[$propertyName][] = 'required';
				$messages[$propertyName.'.required'] = $propertyName;
			}
			if ($property->getRules()) {
				foreach ($property->getRules() as $rule) {
					$rules[$propertyName][] = $rule;
					if (strpos($rule, ':')) {
						list($name, $value) = explode(':', $rule, 2);
						$messages[$propertyName.'.'.$name] = $propertyName;
					} else {
						$messages[$propertyName.'.'.$rule] = $propertyName;
					}
				}
				$messages[$propertyName] = $propertyName;
			}

		}

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden() || $property->getReadonly()) continue;
			$property->setElement($currentElement)->set();
		}

		try {

			$currentElement->save();

			foreach ($propertyList as $propertyName => $property) {
				if (
					$property->getHidden()
					|| $property->getReadonly()
					|| ! $property->getRefresh()
				) continue;
				$view = $property->setElement($currentElement)->getElementEditView();
				$scope['refresh'][$propertyName] = urlencode($view);
			}

			$scope['status'] = 'ok';

		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getCreate(Element $currentElement, $parentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['login'] = null;
			return \View::make('admin::login', $scope);
		}

		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		if ($parentElement) {
			$currentElement->setParent($parentElement);
		}

		$currentItem = $site->getItemByName($currentElement->getClass());

		\View::share('currentElement', $currentElement);
		\View::share('loggedUser', $loggedUser);

		$scope['currentTitle'] = $currentItem->getName().' - Добавление элемента';
		$scope['currentTabTitle'] = $currentItem->getName();

		$scope = CommonFilter::apply($scope);

		$parentList = $currentElement
			? $currentElement->getParentList()
			: array();

		$propertyList = $currentItem->getPropertyList();

		foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden()) {
				unset($propertyList[$propertyName]);
			};
		}

		if ($currentElement->trashed()) {
			$urlOnDelete = \URL::route('admin.trash');
		} elseif ($currentElement->getParent()) {
			$urlOnDelete = $currentElement->getParent()->getBrowseUrl();
		} else {
			$urlOnDelete = \URL::route('admin');
		}

		$scope['parentElement'] = $parentElement;
		$scope['parentList'] = $parentList;
		$scope['currentItem'] = $currentItem;
		$scope['propertyList'] = $propertyList;
		$scope['urlOnDelete'] = $urlOnDelete;
		$scope['mode'] = \Input::get('mode');

		return \View::make('admin::edit', $scope);
	}

	public function getEdit(Element $currentElement)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['login'] = null;
			return \View::make('admin::login', $scope);
		}

		$loggedUser = \Sentry::getUser();

		$site = \App::make('site');

		$currentItem = $site->getItemByName($currentElement->getClass());

		$mainProperty = $currentItem->getMainProperty();

		\View::share('currentElement', $currentElement);
		\View::share('loggedUser', $loggedUser);

		$scope['currentTitle'] = $currentElement->$mainProperty.' - Редактирование элемента';
		$scope['currentTabTitle'] = $currentElement->$mainProperty;

		$scope = CommonFilter::apply($scope);

		$parentElement = $currentElement->getParent();

		$parentList = $currentElement->getParentList();

		$propertyList = $currentItem->getPropertyList();

		foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden()) {
				unset($propertyList[$propertyName]);
			};
		}

		if ($currentElement->trashed()) {
			$urlOnDelete = \URL::route('admin.trash');
		} elseif ($currentElement->getParent()) {
			$urlOnDelete = $currentElement->getParent()->getBrowseUrl();
		} else {
			$urlOnDelete = \URL::route('admin');
		}

		$scope['parentElement'] = $parentElement;
		$scope['parentList'] = $parentList;
		$scope['currentItem'] = $currentItem;
		$scope['propertyList'] = $propertyList;
		$scope['urlOnDelete'] = $urlOnDelete;
		$scope['mode'] = \Input::get('mode');

		return \View::make('admin::edit', $scope);
	}

}