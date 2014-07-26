<?php namespace LemonTree;

use Carbon\Carbon;

class UsersController extends BaseController {

	public function getLog()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		\View::share('loggedUser', $loggedUser);

		$scope['currentTitle'] = 'Журнал действий пользователей';
		$scope['currentTabTitle'] = 'Журнал';

		$scope = CommonFilter::apply($scope);

		$userActionTypeList = UserActionType::getActionTypeNameList();

		$actionType = \Input::get('action_type');
		$comments = \Input::get('comments');
		$dateFrom = \Input::get('date_from');
		$dateTo = \Input::get('date_to');

		if ($actionType && ! UserActionType::actionTypeExists($actionType)) {
			$actionType = null;
		}

		if ($dateFrom) {
			try {
				$dateFrom = Carbon::createFromFormat('Y-m-d', $dateFrom);
			} catch (\Exception $e) {
				$dateFrom = null;
			}
		}

		if ($dateTo) {
			try {
				$dateTo = Carbon::createFromFormat('Y-m-d', $dateTo);
			} catch (\Exception $e) {
				$dateTo = null;
			}
		}

		$userActionListCriteria = UserAction::where(
			function($query) use ($actionType, $comments, $dateFrom, $dateTo) {
				if ($actionType) {
					$query->where('action_type', $actionType);
				}

				if ($comments) {
					$query->where('comments', 'ilike', "%$comments%");
				}

				if ($dateFrom) {
					$query->where('created_at', '>=', $dateFrom->format('Y-m-d'));
				}

				if ($dateTo) {
					$query->where('created_at', '<=', $dateTo->format('Y-m-d'));
				}
			}
		);

		$userActionListCriteria->
		orderBy('created_at', 'desc')->
		cacheTags('UserAction')->
		rememberForever();

		$userActionList = $userActionListCriteria->paginate(100);

		$scope['userActionTypeList'] = $userActionTypeList;
		$scope['actionType'] = $actionType;
		$scope['comments'] = $comments;
		$scope['dateFrom'] = $dateFrom;
		$scope['dateTo'] = $dateTo;
		$scope['userActionList'] = $userActionList;

		return \View::make('admin::userActions', $scope);
	}

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