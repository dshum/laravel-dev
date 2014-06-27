<?php namespace LemonTree;

class CommonFilter {

	public static function apply($scope = array())
	{
		$loggedUser = \Sentry::getUser();

		\View::share('loggedUser', $loggedUser);

		$tabs = $loggedUser->tabs;

		if ( ! sizeof($tabs)) {
			$tab = new Tab;
			$tab->user_id = $loggedUser->id;
			$tab->title = isset($scope['currentTabTitle'])
				? $scope['currentTabTitle'] : 'Lemon Tree';
			$tab->url = \Request::path();
			$tab->is_active = true;
			$tab->show_tree = true;
			$tab->save();
			$tabs[] = $tab;
			$activeTab = $tab;
		} else {
			foreach ($tabs as $tab) {
				if ( ! $tab->is_active) continue;
				$tab->title = isset($scope['currentTabTitle'])
					? $scope['currentTabTitle'] : 'Lemon Tree';
				$tab->url = \Request::path();
				$tab->save();
				$activeTab = $tab;
				break;
			}
		}

		$treeView = 
			$activeTab->show_tree
			? \App::make('LemonTree\TreeController')->show()
			: null;

		$scope['tabs'] = $tabs;
		$scope['activeTab'] = $activeTab;
		$scope['treeView'] = $treeView;

		return $scope;
	}

}
