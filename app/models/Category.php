<?php

class Category extends LemonTree\Element {

	public function getHref()
	{
		return \URL::route('catalogue', array('url' => $this->url));
	}

	public function getFolderHash()
	{
		return null; //substr(md5(rand()), 0, 2).'/'.substr(md5(rand()), 0, 2).'/';
	}

}
