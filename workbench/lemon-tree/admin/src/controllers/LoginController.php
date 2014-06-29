<?php namespace LemonTree;

class LoginController extends BaseController {

	public function postLogin()
	{
		$scope = array();

		try {

			$credentials = \Input::only('login', 'password');

			$user = \Sentry::authenticate($credentials, false);

			\Sentry::login($user, false);

		} catch (\Cartalyst\Sentry\Users\LoginRequiredException $e) {
			$scope['error'] = 'Введите логин.';
		} catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			$scope['error'] = 'Введите пароль.';
		} catch (\Cartalyst\Sentry\Users\WrongPasswordException $e) {
			$scope['error'] = 'Неправильный логин или пароль.';
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			$scope['error'] = 'Неправильный логин или пароль.';
		} catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e) {
			$scope['error'] = 'Пользователь не активирован.';
		} catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
			$scope['error'] = 'Пользователь временно отключен.';
		} catch (\Cartalyst\Sentry\Throttling\UserBannedException $e) {
			$scope['error'] = 'Пользователь заблокирован.';
		}

		if (isset($scope['error'])) {
			$scope['login'] = $credentials['login'];
			return \View::make('admin::login', $scope);
		}

		$loggedUser = \Sentry::getUser();

		$tabs = $loggedUser->getParameter('tabs');
		$currentTab = $loggedUser->getParameter('currentTab');

		if (isset($tabs[$currentTab]['url'])) {
			return \Redirect::to($tabs[$currentTab]['url']);
		}

		return \Redirect::back();
	}

	public function getLogout()
	{
		$scope = array();

		if (\Sentry::check()) \Sentry::logout();

		return \Redirect::route('admin');
	}

	public function getIndex($currentElement = null)
	{
		$scope = array();

		$scope['login'] = null;

		return \View::make('admin::login', $scope);
	}

}