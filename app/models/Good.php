<?php

class Good extends LemonTree\Element {

	public static function boot()
	{
		parent::boot();

		static::saved(function($element) {

			$class = get_class($element);
			if ($class::isCache() === true) {
				$key = $class::getCacheKey($element->id);
				\Cache::forget($key);
				\Cache::tags($class)->flush();
			}

		});

		static::deleted(function($element) {

			$class = get_class($element);
			if ($class::isCache() === true) {
				$key = $class::getCacheKey($element->id);
				\Cache::forget($key);
				\Cache::tags($class)->flush();
			}

		});
    }

	public function getHref()
	{
		$category = $this->category;

		return \URL::route('catalogue', array('url1' => $category->url, 'url2' => $this->url));
	}

	public function getFolderHash()
	{
		return substr(md5(rand()), 0, 2);
	}

	public function category()
	{
		return $this->belongsTo('Category', 'category_id');
	}

	public function subcategory()
	{
		return $this->belongsTo('Subcategory', 'subcategory_id');
	}

	public function brand()
	{
		return $this->belongsTo('GoodBrand', 'good_brand_id');
	}

}
