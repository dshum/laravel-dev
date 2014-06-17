<?php namespace LemonTree;

class GroupController extends BaseController {

	public function getDelete(Group $group)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			return \Redirect::route('admin');
		}

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if (
			$loggedUser->inGroup($group)
			&& ! $loggedUser->isSuperUser()
		) {
			return \Redirect::route('admin.users');
		}

		try {
			$group->delete();
		} catch (\Exception $e) {}

		return \Redirect::route('admin.users');
	}

	public function postAdd()
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		$input = \Input::all();

		$rules = array(
			'name' => 'required',
		);

		$messages = array(
			'name.required' => 'name',
		);

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$group = new Group;

		$group->name = $input['name'];

		try {
			$group->save();
			$scope['status'] = 'ok';
			$scope['redirect'] = \URL::route('admin.users');
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function postSave(Group $group)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		if (
			$loggedUser->inGroup($group)
			&& ! $loggedUser->isSuperUser()
		) {
			$scope['redirect'] = \URL::route('admin.users');
			return json_encode($scope);
		}

		$input = \Input::all();

		$rules = array(
			'name' => 'required',
		);

		$messages = array(
			'name.required' => 'name',
		);

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$group->name = $input['name'];

		$permissions = $group->getPermissions();

		$permissions['admin'] = isset($input['admin']) ? 1 : 0;

		$group->permissions = $permissions;

		try {
			$group->save();
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getCreate()
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			return \Redirect::route('admin');
		}

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		$scope['currentTitle'] = 'Добавление группы';
		$scope['currentTabTitle'] = 'Добавление группы';

		$scope = CommonFilter::apply($scope);

		$group = new Group;

		$scope['group'] = $group;

		return \View::make('admin::group', $scope);
	}

	public function getEdit(Group $group)
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			return \Redirect::route('admin');
		}

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if (
			$loggedUser->inGroup($group)
			&& ! $loggedUser->isSuperUser()
		) {
			return \Redirect::route('admin.users');
		}

		$scope['currentTitle'] = $group->name.' - Редактирование группы';
		$scope['currentTabTitle'] = $group->name;

		$scope = CommonFilter::apply($scope);

		$scope['group'] = $group;

		return \View::make('admin::group', $scope);
	}

}