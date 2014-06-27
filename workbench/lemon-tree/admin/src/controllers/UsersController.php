<?php namespace LemonTree;

class UsersController extends BaseController {

	public function getGroup(Group $activeGroup)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		\View::share('loggedUser', $loggedUser);

		$scope['currentTitle'] = 'Управление пользователями';
		$scope['currentTabTitle'] = 'Управление пользователями';

		$scope = CommonFilter::apply($scope);

		$groupList = Group::orderBy('name', 'asc')->get();

		$userList = $activeGroup->users()->orderBy('login', 'asc')->get();

		$groupMap = array();

		foreach ($userList as $user) {
			foreach ($user->getGroups() as $group) {
				$groupMap[$user->id][$group->id] = $group->id;
			}
		}

		$scope['activeGroup'] = $activeGroup;
		$scope['groupList'] = $groupList;
		$scope['userList'] = $userList;
		$scope['groupMap'] = $groupMap;

		return \View::make('admin::users', $scope);
	}

	public function getIndex()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		\View::share('loggedUser', $loggedUser);

		$scope['currentTitle'] = 'Управление пользователями';
		$scope['currentTabTitle'] = 'Управление пользователями';

		$scope = CommonFilter::apply($scope);

		$groupList = Group::orderBy('name', 'asc')->get();

		$userList = User::orderBy('login', 'asc')->get();

		$groupMap = array();

		foreach ($userList as $user) {
			foreach ($user->getGroups() as $group) {
				$groupMap[$user->id][$group->id] = $group->id;
			}
		}

		$scope['groupList'] = $groupList;
		$scope['userList'] = $userList;
		$scope['groupMap'] = $groupMap;

		return \View::make('admin::users', $scope);
	}

}