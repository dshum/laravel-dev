<?php namespace LemonTree;

class FileProperty extends BaseProperty {

	protected static $dirmod = 0755;
	protected static $filemod = 0644;

	protected $folderName = null;
	protected $hash = null;
	protected $folderPath = null;
	protected $folderWebPath = null;

	protected $maxSize = 8192;
	protected $allowedMimeTypes = array(
		'txt', 'pdf', 'xls', 'xlsx', 'ppt', 'doc', 'docx', 'xml',
		'gif', 'jpeg', 'pjpeg', 'png', 'tiff', 'ico',
		'zip', 'rar', 'tar',
	);

	protected $rules = array();

	public function __construct($name) {
		parent::__construct($name);

		$this->
		addRule('max:'.$this->maxSize)->
		addRule('mimes:'.join(',', $this->allowedMimeTypes));

		return $this;
	}

	public static function create($name)
	{
		return new self($name);
	}

	public function getRefresh()
	{
		return true;
	}

	public function setMaxSize($maxSize)
	{
		$this->maxSize = $maxSize;

		return $this;
	}

	public function getMaxSize()
	{
		return $this->maxSize;
	}

	public function path()
	{
		return $this->getItemClass()->getFolderWebPath().$this->getValue();
	}

	public function abspath()
	{
		return $this->getItemClass()->getFolderPath().$this->getValue();
	}

	public function filename()
	{
		return basename($this->getValue());
	}

	public function filesize()
	{
		return $this->exists() ? filesize($this->abspath()) : 0;
	}

	public function filesize_kb($precision = 0)
	{
		return round($this->filesize() / 1024, $precision);
	}

	public function filesize_mb($precision = 0)
	{
		return round($this->filesize() / 1024 / 1024, $precision);
	}

	public function exists()
	{
		return $this->getValue() && file_exists($this->abspath());
	}

	public function set()
	{
		$name = $this->getName();

		if (\Input::hasFile($name)) {

			$file = \Input::file($name);

			if ($file->isValid() && $file->getMimeType()) {

				$this->drop();

				$original = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();

				if ( ! $extension) $extension = 'txt';

				$filename = sprintf('%s_%s.%s',
					$name,
					substr(md5(rand()), 0, 8),
					$extension
				);

				$folderHash = $this->element->getFolderHash();

				$destination = $this->element->getFolderPath().$folderHash;

				$file->move($destination, $filename);

				$this->element->$name = $folderHash.$filename;
			}

		} elseif (\Input::get($name.'_drop')) {

			$this->drop();

			$this->element->$name = null;

		}

		return $this;
	}

	public function drop()
	{
		if ($this->exists()) {
			try {
				unlink($this->abspath());
			} catch (\Exception $e) {}
		}
	}

	public function getElementListView()
	{
		$scope = array(
			'path' => $this->path(),
			'value' => $this->getValue(),
		);

		try {
			$view = $this->getClassName().'.elementList';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {}

		return null;
	}

	public function getElementEditView()
	{
		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $this->getValue(),
			'readonly' => $this->getReadonly(),
			'exists' => $this->exists(),
			'path' => $this->path(),
			'filesize' => $this->filesize_kb(1),
			'filename' => $this->filename(),
			'maxFilesize' => $this->getMaxSize(),
		);

		try {
			$view = $this->getClassName().'.elementEdit';
			return \View::make('admin::properties.'.$view, $scope);
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		return null;
	}

}
