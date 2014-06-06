<?php

class Subcategory extends LemonTree\Element {

	protected $category = null;
	
	public function getHref()
	{
		$category = $this->getCategory();

		return \URL::route('catalogue', array('url1' => $category->url, 'url2' => $this->url));
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

}
