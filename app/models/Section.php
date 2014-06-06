<?php

class Section extends LemonTree\Element {

	public function getHref()
	{
		return URL::route($this->url);
	}

}
