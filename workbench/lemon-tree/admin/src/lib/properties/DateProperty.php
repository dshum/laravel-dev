<?php namespace LemonTree;

use Carbon\Carbon;

class DateProperty extends BaseProperty {

	protected $format = 'Y-m-d';
	protected $rules = array('date_format:"Y-m-d"');

	protected $fillNow = false;

	public static function create($name)
	{
		return new self($name);
	}

	public function setFillNow($fillNow)
	{
		$this->fillNow = $fillNow;

		return $this;
	}

	public function getFillNow()
	{
		return $this->fillNow;
	}

	public function setElement(Element $element)
	{
		parent::setElement($element);

		if (is_string($this->value)) {
			try {
				$this->value = Carbon::createFromFormat($this->format, $this->value);
				return $this;
			} catch (\Exception $e) {}
		}

		if ( ! $this->value && $this->getFillNow()) {
			$this->value = \Carbon::now();
		}

		return $this;
	}

	public function searchQuery($query)
	{
		$name = $this->getName();

		$from = \Input::get($name.'_from');
		$to = \Input::get($name.'_to');

		if ($from) {
			try {
				$from = Carbon::createFromFormat('Y-m-d', $from);
				$query->where($name, '>=', $from->format('Y-m-d'));
			} catch (\Exception $e) {}
		}

		try {
			$from = Carbon::createFromFormat('Y-m-d', $from);
			$to = Carbon::createFromFormat('Y-m-d', $to);
		} catch (\Exception $e) {
			$from = null;
			$to = null;
		}

		return $query;
	}

	public function getElementSearchView()
	{
		$from = \Input::get($this->getName().'_from');
		$to = \Input::get($this->getName().'_to');

		try {
			$from = Carbon::createFromFormat('Y-m-d', $from);
			$to = Carbon::createFromFormat('Y-m-d', $to);
		} catch (\Exception $e) {
			$from = null;
			$to = null;
		}

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'from' => $from,
			'to' => $to,
		);

		try {
			$view = $this->getClassName().'.elementSearch';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

}
