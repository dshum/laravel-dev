<?php namespace LemonTree;

class TreeFilter {

	public static function apply($scope = array()) 
	{
		$treeView = \App::make('LemonTree\TreeController')->show();
		
		$scope['treeView'] = $treeView;
		
		return $scope;
	}

}
