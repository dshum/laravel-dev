<?php

class LoginController extends BaseController {

	public function postLogin()
	{
		$scope = array();

		$credentials = Input::only('email', 'password');
		$remember = Input::get('remember') ? true : false;

		if ( ! $credentials['email']) {
			$scope['error'] = 'Введите e-mail.';
		} elseif ( ! $credentials['password']) {
			$scope['error'] = 'Введите пароль.';
		} elseif ( ! Auth::attempt($credentials, $remember)) {
			$scope['error'] = 'Неправильный e-mail или пароль.';
		} else {
			$scope['error'] = null;
		}

		if ($scope['error']) {
			$scope = CommonFilter::apply($scope);
			$scope['email'] = $credentials['email'];
			$scope['remember'] = $remember;
			return View::make('user.login', $scope);
		}

		return \Redirect::back();
	}

	public function getLogout()
	{
		$scope = array();

		Auth::logout();

		return \Redirect::back();
	}

	public function getIndex()
	{
		$scope = array();

		$scope = CommonFilter::apply($scope);

		$scope['email'] = null;
		$scope['remember'] = false;

		return View::make('user.login', $scope);
	}

}
