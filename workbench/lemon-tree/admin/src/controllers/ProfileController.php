<?php namespace LemonTree;

class ProfileController extends BaseController {

	public function postUpdate()
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			$scope['logout'] = true;
			return json_encode($scope);
		}

		$loggedUser = \Sentry::getUser();

		$input = \Input::all();

		$rules = array(
			'email' => 'required|email',
			'first_name' => 'required',
			'last_name' => 'required',
		);

		$messages = array(
			'password' => 'password',
			'email.required' => 'email',
			'email' => 'email',
			'first_name.required' => 'first_name',
			'last_name.required' => 'last_name',
		);

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		if ($input['password']) {
			$loggedUser->password = $input['password'];
		}
		$loggedUser->email = $input['email'];
		$loggedUser->first_name = $input['first_name'];
		$loggedUser->last_name = $input['last_name'];

		try {
			$loggedUser->save();
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getIndex()
	{
		$scope = array();

		if ( ! \Sentry::check()) {
			return \Redirect::route('admin');
		}

		$loggedUser = \Sentry::getUser();

		$groups = $loggedUser->getGroups();

		$scope['currentTitle'] = $loggedUser->login.' - Редактирование профиля';
		$scope['currentTabTitle'] = $loggedUser->login;

		$scope = CommonFilter::apply($scope);

		$scope['groups'] = $groups;
		$scope['mode'] = \Input::get('mode');

		return \View::make('admin::profile', $scope);
	}

}