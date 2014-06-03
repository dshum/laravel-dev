<?php namespace LemonTree;

class EditController extends BaseController {

	const HINT_LIMIT = 20;

	public function getHint($class)
	{
		$scope = array();

		$site = \App::make('site');

		$item = $site->getItemByName($class);
		$mainProperty = $item->getMainProperty();

		$term = \Input::get('term');

		$elementListCriteria = $class::cacheTags($class)->rememberForever();

		if ($term) {
			$elementListCriteria->
			where(
				'id', 'like', '%'.$term.'%'
			)->
			orWhere(
				$mainProperty, 'like', '%'.$term.'%'
			);
		}

		$elementList =
			$elementListCriteria->
			limit(static::HINT_LIMIT)->
			get();

		$prev = null;
		$k = 2;

		foreach ($elementList as $element) {
			$id = $element->id;
			$name = $element->$mainProperty;
			if ($prev == $name) {
				$name = $name.' '.$k;
				$k++;
			} else {
				$name = $name;
				$k = 2;
			}
			$scope[] = array(
				'id' => $id,
				'value' => $name,
			);
			$prev = $element->$mainProperty;
		}

		return json_encode($scope);
	}

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

		if ($parentElement) {
			$currentElement->setParent($parentElement);
		}

		\View::share('currentElement', $currentElement);
		\View::share('loggedUser', $loggedUser);

		$scope = TreeFilter::apply($scope);

		$parentList = $currentElement
			? $currentElement->getParentList()
			: array();

		$site = \App::make('site');

		$currentItem = $site->getItemByName($currentElement->getClass());

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

		\View::share('currentElement', $currentElement);
		\View::share('loggedUser', $loggedUser);

		$scope = TreeFilter::apply($scope);

		$parentElement = $currentElement->getParent();

		$parentList = $currentElement->getParentList();

		$site = \App::make('site');

		$currentItem = $site->getItemByName($currentElement->getClass());

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