<?php namespace LemonTree;

class TabController extends BaseController {
	
	public function postToggle(Tab $activeTab)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		$open = \Input::get('open');

		$treeView = null;

		if ($open == 'open') {
			$treeView = \App::make('LemonTree\TreeController')->show();
			$activeTab->show_tree = true;
		} elseif ($open == 'false') {
			$activeTab->show_tree = true;
		} else {
			$activeTab->show_tree = false;
		}

		$activeTab->save();

		return $treeView;
	}

	public function getAddTab()
	{
		$loggedUser = \Sentry::getUser();

		$tabs = $loggedUser->tabs;

		foreach ($tabs as $tab) {
			if ($tab->is_active) {
				$tab->is_active = false;
				$tab->save();
			}
		}

		$tab = new Tab;
		$tab->user_id = $loggedUser->id;
		$tab->title = 'Lemon Tree';
		$tab->url = \URL::route('admin');
		$tab->is_active = true;
		$tab->show_tree = false;
		$tab->save();

		return \Redirect::to($tab->url);
	}

	public function getDeleteTab(Tab $delTab)
	{
		$loggedUser = \Sentry::getUser();

		if ($delTab->user_id != $loggedUser->id) {
			return \Redirect::route('admin');
		}

		$tabs = $loggedUser->tabs;

		$activeTab = null;

		$prev = null;
		$next = false;

		foreach ($tabs as $tab) {
			if (
				$tab->is_active
				&& $tab->id == $delTab->id
			) {
				$next = true;
				if ($prev) {
					$delTab->delete();
					$prev->is_active = true;
					$prev->save();
					$activeTab = $prev;
					break;
				}
			} elseif ($next) {
				$delTab->delete();
				$tab->is_active = true;
				$tab->save();
				$activeTab = $tab;
				break;
			} elseif ($tab->id == $delTab->id) {
				$delTab->delete();
				break;
			} elseif ($tab->is_active) {
				$activeTab = $tab;
			}
			$prev = $tab;
		}

		return $activeTab
			? \Redirect::to($activeTab->url)
			: \Redirect::route('admin');
	}

	public function getIndex(Tab $activeTab)
	{
		$loggedUser = \Sentry::getUser();

		if ($activeTab->user_id != $loggedUser->id) {
			return \Redirect::route('admin');
		}

		$tabs = $loggedUser->tabs;

		foreach ($tabs as $tab) {
			if ($tab->is_active) {
				$tab->is_active = false;
				$tab->save();
			}
		}

		$activeTab->is_active = true;

		$activeTab->save();

		return \Redirect::to($activeTab->url);
	}

}