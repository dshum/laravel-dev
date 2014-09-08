<?php namespace LemonTree;

class OrderController extends BaseController {
	
	public function postSave($class)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();
		
		$site = \App::make('site');
		$item = $site->getItemByName($class);

		$orderList = \Input::get('orderList');

		if ( 
			! $item instanceof Item 
			|| ! $orderList
		) {
			return json_encode($scope);
		}
		
		$orderProperty = $item->getOrderProperty();
		
		$saved = array();

		foreach ($orderList as $classId => $order) {
			$element = Element::getByClassId($classId);
			if ($element) {
				$element->$orderProperty = $order;
				$element->save();
				$saved[] = $element->getClassId();
			}
		}
		
		if (sizeof($saved)) {
			UserAction::log(
				UserActionType::ACTION_TYPE_ORDER_ELEMENT_LIST_ID,
				implode(', ', $saved)
			);
		}
		
		return json_encode($scope);
	}

	public function getIndex(Item $item, $currentElement = null)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();
		
		if (
			$currentElement
			&& ! $loggedUser->hasViewAccess($currentElement)
		) {
			return \Redirect::route('admin');
		}

		\View::share('item', $item);
		\View::share('currentElement', $currentElement);
		
		$scope['currentTitle'] = 'Порядок элементов';
		$scope['currentTabTitle'] = 'Порядок элементов';
		
		$scope = CommonFilter::apply($scope);
		
		$parentList = $currentElement
			? $currentElement->getParentList()
			: array();
		
		$propertyList = $item->getPropertyList();
		$orderByList = $item->getOrderByList();
		$mainProperty = $item->getMainProperty();
		
		$elementListCriteria = $item->getClass()->where(
			function($query) use ($propertyList, $currentElement) {
				if ($currentElement) {
					$query->orWhere('id', null);
				}
				foreach ($propertyList as $propertyName => $property) {
					if (
						$currentElement
						&& $property instanceof OneToOneProperty
						&& $property->getRelatedClass() == $currentElement->getClass()
					) {
						$query->orWhere(
							$property->getName(), $currentElement->id
						);
					} elseif (
						! $currentElement
						&& $property instanceof OneToOneProperty
					) {
						$query->orWhere(
							$property->getName(), null
						);
					}
				}
			}
		);
		
		foreach ($orderByList as $field => $direction) {
			$elementListCriteria->orderBy($field, $direction);
		}
		
		$elementListCriteria->
		cacheTags($item->getName())->
		rememberForever();
		
		$elementList = $elementListCriteria->get();
		
		$hiddens = array();
		$options = array();
		
		foreach ($elementList as $k => $element) {
			$hiddens[$element->getClassId()] = $k;
			$options[$element->getClassId()] = $element->$mainProperty;
		}
		
		unset($elementList);
		
		$scope['parentList'] = $parentList;
		$scope['propertyList'] = $propertyList;
		$scope['hiddens'] = $hiddens;
		$scope['options'] = $options;

		return \View::make('admin::order', $scope);
	}

}