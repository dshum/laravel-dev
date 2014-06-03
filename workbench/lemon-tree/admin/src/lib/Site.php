<?php namespace LemonTree;

class Site {
	
	const ROOT = 'Root';

	protected $items = array();
	protected $binds = array();
	protected $bindsTree = array();

	protected $initMicroTime = null;

	public function addItem(Item $item)
	{
		$this->items[$item->getName()] = $item;

		return $this;
	}
	
	public function getItemList()
	{
		return $this->items;
	}

	public function getItemByName($name)
	{
		return
			isset($this->items[$name])
			? $this->items[$name]
			: null;
	}
	
	public function bind()
	{
		$num = func_num_args();
		$args = func_get_args();
		
		if ($num < 2) return $this;
		
		$name = array_shift($args);
		
		foreach ($args as $arg) {
			$this->binds[$name][$arg] = $arg;
		}
		
		return $this;
	}
	
	public function getBinds()
	{
		return $this->binds;
	}
	
	public function bindTree()
	{
		$num = func_num_args();
		$args = func_get_args();
		
		if ($num < 2) return $this;
		
		$name = array_shift($args);
		
		foreach ($args as $arg) {
			$this->bindsTree[$name][$arg] = $arg;
		}
		
		return $this;
	}
	
	public function getBindsTree()
	{
		return $this->bindsTree;
	}
	
	public function end()
	{
		return $this;
	}

	public function initMicroTime()
	{
		$this->initMicroTime = explode(' ', microtime());
	}

	public function getMicroTime()
	{
		list($usec1, $sec1) = explode(' ', microtime());
		list($usec0, $sec0) = $this->initMicroTime;

		$time = (float)$sec1 + (float)$usec1 - (float)$sec0 - (float)$usec0;

		return round($time, 6);
	}

	public function getMemoryUsage()
	{
		return round(memory_get_peak_usage() / 1024 / 1024, 2);
	}

}
