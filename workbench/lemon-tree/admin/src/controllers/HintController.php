<?php namespace LemonTree;

class HintController extends BaseController {

	const HINT_LIMIT = 20;

	public function getHint($class)
	{
		$scope = array();

		$site = \App::make('site');

		$item = $site->getItemByName($class);
		$mainProperty = $item->getMainProperty();

		$term = \Input::get('term');

		$elementListCriteria = $class::query();

		if ($term) {
			$elementListCriteria->
			whereRaw(
				"cast(id as text) like :term or $mainProperty like :term",
				array('term' => '%'.$term.'%')
			);
		}

		$orderByList = $item->getOrderByList();

		foreach ($orderByList as $field => $direction) {
			$elementListCriteria->orderBy($field, $direction);
		}

		$elementListCriteria->
		limit(static::HINT_LIMIT);

		$elementListCriteria->
		cacheTags($class)->
		rememberForever();

		$elementList = $elementListCriteria->get();

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

	public function getMultiHint($itemName, $propertyName)
	{
		$scope = array();

		$site = \App::make('site');

		$item = $site->getItemByName($itemName);
		$property = $item ? $item->getPropertyByName($propertyName) : null;
		$items = $property ? $property->getItems() : null;

		if ( ! $items) return $scope;

		$term = \Input::get('term');

		$prev = null;
		$k = 2;

		foreach ($items as $itemName) {

			$item = $site->getItemByName($itemName);

			if ( ! $item) continue;

			$mainProperty = $item->getMainProperty();

			$elementListCriteria = $itemName::query();

			if ($term) {
				$elementListCriteria->
				where(
					'id', 'like', '%'.$term.'%'
				)->
				orWhere(
					$mainProperty, 'like', '%'.$term.'%'
				);
			}

			$orderByList = $item->getOrderByList();

			foreach ($orderByList as $field => $direction) {
				$elementListCriteria->orderBy($field, $direction);
			}

			$elementListCriteria->
			limit(static::HINT_LIMIT);

			$elementListCriteria->
			cacheTags($itemName)->
			rememberForever();

			$elementList = $elementListCriteria->get();

			foreach ($elementList as $element) {
				$id = $element->getClassId();
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

		}

		$scope = array_slice($scope, 0, static::HINT_LIMIT);

		return json_encode($scope);
	}

}
