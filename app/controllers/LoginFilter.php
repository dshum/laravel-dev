<?php

class LoginFilter {

	public static function apply($scope = array()) {

		if (Auth::check()) {
			$loggedUser = Auth::user();
		} else {
			$loggedUser = null;
		}

		View::share('loggedUser', $loggedUser);

		return $scope;
	}

}
