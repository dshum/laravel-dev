<?php namespace LemonTree;

class IntegerProperty extends BaseProperty {

	protected $rules = array('integer');

	public static function create($name)
	{
		return new self($name);
	}

	public function searchQuery($query)
	{
		$name = $this->getName();

		$from = \Input::get($name.'_from');
		$to = \Input::get($name.'_to');

		if (strlen($from)) {
			$from = str_replace(array(',', ' '), array('.', ''), $from);
			$query->where($name, '>=', (int)$from);
		}

		if (strlen($to)) {
			$to = str_replace(array(',', ' '), array('.', ''), $to);
			$query->where($name, '<=', (int)$to);
		}

		return $query;
	}

	public function getElementSearchView()
	{
		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'from' => \Input::get($this->getName().'_from'),
			'to' => \Input::get($this->getName().'_to'),
		);

		try {
			$view = $this->getClassName().'.elementSearch';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

}
