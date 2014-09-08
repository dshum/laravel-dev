<?php namespace LemonTree;

use Carbon\Carbon;

class TimeProperty extends BaseProperty {

	protected $format = 'H:i:s';
	protected $rules = array('date_format:"H:i:s"');

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
			$this->value = Carbon::createFromFormat($this->format, $this->value);
		} elseif ( ! $this->value && $this->getFillNow()) {
			$this->value = \Carbon::now();
		}

		return $this;
	}

	public function searchQuery($query)
	{
		$name = $this->getName();

		$from = \Input::get($name.'_from');
		$to = \Input::get($name.'_to');

		if ($from !== null) {
			$from = str_replace(array(',', ' '), array('.', ''), $from);
			$query->where($name, '>=', (int)$from);
		}

		if ($to !== null) {
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
