<?php namespace LemonTree;

class UserController extends BaseController {

	public function getDelete(User $user)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if (
			$loggedUser->id == $user->id
			|| $user->isSuperUser()
		) {
			return \Redirect::route('admin.users');
		}

		try {
			$user->delete();
			UserAction::log(
				UserActionType::ACTION_TYPE_DROP_USER_ID,
				'ID '.$user->id.' ('.$user->login.')'
			);
		} catch (\Exception $e) {}

		return \Redirect::route('admin.users');
	}

	public function postAdd()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		$input = \Input::all();

		$rules = array(
			'login' => 'required',
			'password' => 'required',
			'email' => 'required|email',
			'first_name' => 'required',
			'last_name' => 'required',
		);

		$messages = array(
			'login.required' => 'login',
			'password.required' => 'password',
			'email.required' => 'email',
			'email' => 'email',
			'first_name.required' => 'first_name',
			'last_name.required' => 'last_name',
		);

		$groups = array();

		foreach ($input as $key => $value) {
			if (strpos($key, 'group_') !== false) {
				$groups[$key] = $value;
			}
		}

		foreach ($groups as $name => $id) {
			$rules[$name] = 'exists:cytrus_groups,id';
			$messages[$name.'.exists'] = $name;
		}

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$user = new User;

		$user->activated = true;
		$user->login = $input['login'];
		if ($input['password']) {
			$user->password = $input['password'];
		}
		$user->email = $input['email'];
		$user->first_name = $input['first_name'];
		$user->last_name = $input['last_name'];

		try {

			$user->save();

			foreach ($groups as $name => $id) {
				$group = \Sentry::findGroupById($id);
				$user->addGroup($group);
			}

			$user->flush();

			UserAction::log(
				UserActionType::ACTION_TYPE_ADD_USER_ID,
				'ID '.$user->id.' ('.$user->login.')'
			);

			$scope['status'] = 'ok';

			$scope['redirect'] = \URL::route('admin.users');

		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function postSave(User $user)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		if (
			$loggedUser->id == $user->id
			|| $user->isSuperUser()
		) {
			$scope['redirect'] = \URL::route('admin.users');
			return json_encode($scope);
		}

		$input = \Input::all();

		$rules = array(
			'login' => 'required',
			'email' => 'required|email',
			'first_name' => 'required',
			'last_name' => 'required',
		);

		$messages = array(
			'login.required' => 'login',
			'email.required' => 'email',
			'email' => 'email',
			'first_name.required' => 'first_name',
			'last_name.required' => 'last_name',
		);

		$groups = array();

		foreach ($input as $key => $value) {
			if (strpos($key, 'group_') !== false) {
				$groups[$key] = $value;
			}
		}

		foreach ($groups as $name => $id) {
			$rules[$name] = 'exists:cytrus_groups,id';
			$messages[$name.'.exists'] = $name;
		}

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$user->login = $input['login'];
		if ($input['password']) {
			$user->password = $input['password'];
		}
		$user->email = $input['email'];
		$user->first_name = $input['first_name'];
		$user->last_name = $input['last_name'];

		try {

			$user->save();

			$userGroups = $user->getGroups();

			foreach ($userGroups as $userGroup) {
				if ( ! in_array($userGroup->id, $groups)) {
					$user->removeGroup($userGroup);
				}
			}

			foreach ($groups as $name => $id) {
				$group = \Sentry::findGroupById($id);
				$user->addGroup($group);
			}

			$user->flush();

			UserAction::log(
				UserActionType::ACTION_TYPE_SAVE_USER_ID,
				'ID '.$user->id.' ('.$user->login.')'
			);

			$scope['status'] = 'ok';

		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getCreate()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		$scope['currentTitle'] = 'Добавление пользователя';
		$scope['currentTabTitle'] = 'Добавление пользователя';

		$scope = CommonFilter::apply($scope);

		$user = new User;

		$groupList = Group::orderBy('name', 'asc')->get();

		$userGroups = array();

		$scope['user'] = $user;
		$scope['groupList'] = $groupList;
		$scope['userGroups'] = $userGroups;

		return \View::make('admin::user', $scope);
	}

	public function getEdit(User $user)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if (
			$loggedUser->id == $user->id
			|| $user->isSuperUser()
		) {
			return \Redirect::route('admin.users');
		}

		$scope['currentTitle'] = $user->login.' - Редактирование пользователя';
		$scope['currentTabTitle'] = $user->login;

		$scope = CommonFilter::apply($scope);

		$groupList = Group::orderBy('name', 'asc')->get();

		$groups = $user->getGroups();

		$userGroups = array();

		foreach ($groups as $group) {
			$userGroups[$group->id] = $group;
		}

		$scope['user'] = $user;
		$scope['groupList'] = $groupList;
		$scope['userGroups'] = $userGroups;

		return \View::make('admin::user', $scope);
	}

}