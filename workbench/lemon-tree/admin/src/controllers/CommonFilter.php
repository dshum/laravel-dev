<?php namespace LemonTree;

class CommonFilter {

	public static function apply($scope = array())
	{
		$loggedUser = \Sentry::getUser();

		$tabs = $loggedUser->getTabs();

		if ( ! sizeof($tabs)) {
			$tab = new Tab;
			$tab->user_id = $loggedUser->id;
			$tab->title = isset($scope['currentTabTitle'])
				? $scope['currentTabTitle'] : 'Lemon Tree';
			$tab->url = \Request::path();
			$tab->is_active = true;
			$tab->save();
			$tabs[] = $tab;
		} else {
			foreach ($tabs as $tab) {
				if ( ! $tab->is_active) continue;
				$tab->title = isset($scope['currentTabTitle'])
					? $scope['currentTabTitle'] : 'Lemon Tree';
				$tab->url = \Request::path();
				$tab->save();
				break;
			}
		}

		$treeView = \App::make('LemonTree\TreeController')->show();

		$scope['tabs'] = $tabs;
		$scope['treeView'] = $treeView;

		return $scope;
	}

}
