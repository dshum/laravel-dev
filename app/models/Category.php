<?php

class Category extends LemonTree\Element {

	public function getHref()
	{
		return \URL::route('catalogue', array('url' => $this->url));
	}

}
