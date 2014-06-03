<?php namespace LemonTree;

use Carbon\Carbon;

class DatetimeProperty extends BaseProperty {

	protected $format = 'Y-m-d H:i:s';
	protected $rules = array('date_format:"Y-m-d H:i:s"');

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

}
