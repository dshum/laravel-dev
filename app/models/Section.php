<?php

class Section extends LemonTree\Element {

	protected $href = null;

	public function getHref()
	{
		return URL::route('section', array($this->url));
	}

}
