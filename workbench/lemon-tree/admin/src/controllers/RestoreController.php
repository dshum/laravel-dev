<?php namespace LemonTree;

class RestoreController extends BaseController {

	public function postReset()
	{
		$scope = array();

		if (\Sentry::check()) {
			return \Redirect::route('admin');
		}

		$login = \Input::get('login');
		$code = \Input::get('code');
		$password = \Input::get('password');

		if ( ! $login || ! $code) {
			return \Redirect::route('admin.login.reset');
		}

		if (! $password) {
			$scope['mode'] = null;
			$scope['error'] = 'password';
			$scope['login'] = \Input::get('login');
			$scope['code'] = \Input::get('code');
			return \View::make('admin::reset', $scope);
		}

		try {
			$user = \Sentry::findUserByLogin($login);
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			return \Redirect::route('admin.login.reset', array('error' => 'login'));
		}

		if ( ! $user->checkResetPasswordCode($code)) {
			return \Redirect::route('admin.login.reset', array('error' => 'code'));
		}

		if ( ! $user->attemptResetPassword($code, $password)) {
			return \Redirect::route('admin.login.reset', array('error' => 'reset'));
		}

		return \Redirect::route('admin.login.reset', array('mode' => 'ok'));
	}

	public function getReset()
	{
		$scope = array();

		if (\Sentry::check()) {
			return \Redirect::route('admin');
		}

		$scope['mode'] = \Input::get('mode');
		$scope['error'] = \Input::get('error');
		$scope['login'] = \Input::get('login');
		$scope['code'] = \Input::get('code');

		return \View::make('admin::reset', $scope);
	}

	public function postRestore()
	{
		$scope = array();

		if (\Sentry::check()) {
			return \Redirect::route('admin');
		}

		$login = \Input::get('login');

		if ( ! $login) {
			$scope['error'] = 'Введите логин.';
			$scope['login'] = $login;
			return \View::make('admin::restore', $scope);
		}

		try {
			$user = \Sentry::findUserByLogin($login);
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			$scope['error'] = 'Пользователь с таким логином не найден.';
			$scope['login'] = $login;
			return \View::make('admin::restore', $scope);
		}

		$resetCode = $user->getResetPasswordCode();

		$data = array(
			'login' => $user->login,
			'url' => \URL::route('admin.reset', array(
				'login' => $user->login,
				'code' => $resetCode
			)),
		);

		\Mail::send('admin::mail.restore', $data, function($message) use ($user) {
			$message->
			from('info@lemon-tree.ru', 'Lemon Tree')->
			to($user->email, $user->first_name.' '.$user->last_name)->
			subject('Lemon Tree - восстановление пароля');
		});

		return \Redirect::route('admin.login.restore', array('email' => $user->email));
	}

	public function getIndex()
	{
		$scope = array();

		if (\Sentry::check()) {
			return \Redirect::route('admin');
		}

		$email = \Input::get('email');

		$scope['login'] = null;
		$scope['email'] = $email;

		return \View::make('admin::restore', $scope);
	}

}