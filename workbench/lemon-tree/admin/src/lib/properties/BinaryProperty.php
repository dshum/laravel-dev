<?php namespace LemonTree;

class BinaryProperty extends BaseProperty {

	const MAX_SIZE = 16384;

	protected $rules = array("max:16384");

	public static function create($name)
	{
		return new self($name);
	}

	public function getRefresh()
	{
		return true;
	}

	public function set()
	{
		$name = $this->getName();

		if (\Input::hasFile($name)) {
			$file = \Input::file($name);
			if ($file->isValid()) {
				$data = file_get_contents($file->getRealPath());
				$this->element->$name = $data;
				unlink($file->getRealPath());
			}
		} elseif (\Input::get($name.'_drop')) {
			$this->element->$name = null;
		}

		return $this;
	}

}
