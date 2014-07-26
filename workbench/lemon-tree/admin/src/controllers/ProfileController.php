<?php namespace LemonTree;

class ProfileController extends BaseController {

	public function postUpdate()
	{
		$scope = array();

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
			UserAction::log(
				UserActionType::ACTION_TYPE_SAVE_PROFILE_ID,
				'ID '.$loggedUser->id.' ('.$loggedUser->login.')'
			);
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['error'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getIndex()
	{
		$scope = array();

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