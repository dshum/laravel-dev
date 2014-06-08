<?php

class Good extends LemonTree\Element {

	protected $category = null;
	protected $subcategory = null;

	public function getHref()
	{
		$category = $this->getCategory();

		return \URL::route('catalogue', array('url1' => $category->url, 'url2' => $this->url));
	}

	public function getFolderHash()
	{
		return substr(md5(rand()), 0, 2);
	}

	public function getCategory()
	{
		if ($this->category) return $this->category;

		return $this->category =
			$this->belongsTo('Category', 'category_id')->
			cacheTags('Category')->
			rememberForever()->
			first();
	}

	public function getSubcategory()
	{
		if ($this->subcategory) return $this->subcategory;

		return $this->subcategory =
			$this->belongsTo('Subcategory', 'subcategory_id')->
			cacheTags('Subcategory')->
			rememberForever()->
			first();
	}

}
