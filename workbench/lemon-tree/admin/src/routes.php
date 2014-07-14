<?php

Route::filter('admin.guest', function() {
	if (\Sentry::check()) {
		return Redirect::route('admin');
	}
});

Route::filter('admin.auth', function() {
	if ( ! \Sentry::check()) {
		$scope['login'] = null;
		return \View::make('admin::login', $scope);
	}
});

Route::filter('admin.auth.post', function() {
	if ( ! \Sentry::check()) {
		return Redirect::route('admin');
	}
});

Route::filter('admin.auth.ajax', function() {
	if ( ! \Sentry::check()) {
		$scope['logout'] = true;
		return json_encode($scope);
	}
});

Route::filter('admin.auth.ajax.html', function() {
	if ( ! \Sentry::check()) {
		return null;
	}
});

Route::group(array('before' => 'admin.guest'), function() {

	Route::get('/admin/login', array('as' => 'admin.login', 'uses' => 'LemonTree\LoginController@getIndex'));

	Route::get('/admin/login/restore', array('as' => 'admin.login.restore', 'uses' => 'LemonTree\RestoreController@getIndex'));

	Route::get('/admin/login/reset', array('as' => 'admin.login.reset', 'uses' => 'LemonTree\RestoreController@getReset'));

	Route::post('/admin/login', array('as' => 'admin.login', 'uses' => 'LemonTree\LoginController@postLogin'));

	Route::post('/admin/login/restore', array('as' => 'admin.login.restore', 'uses' => 'LemonTree\RestoreController@postRestore'));

	Route::post('/admin/login/reset', array('as' => 'admin.login.reset', 'uses' => 'LemonTree\RestoreController@postReset'));

});

Route::group(array('before' => 'admin.auth'), function() {

	Route::get('/admin', array('as' => 'admin', 'uses' => 'LemonTree\BrowseController@getIndex'));

	Route::get('/admin/logout', array('as' => 'admin.logout', 'uses' => 'LemonTree\LoginController@getLogout'));

	Route::get('/admin/profile', array('as' => 'admin.profile', 'uses' => 'LemonTree\ProfileController@getIndex'));

	Route::get('/admin/search', array('as' => 'admin.search', 'uses' => 'LemonTree\SearchController@getIndex'));

	Route::get('/admin/trash', array('as' => 'admin.trash', 'uses' => 'LemonTree\BrowseController@getIndex'));

	Route::get('/admin/users', array('as' => 'admin.users', 'uses' => 'LemonTree\UsersController@getIndex'));

	Route::get('/admin/users/group/{id}', array('as' => 'admin.users.group', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\UsersController')->getGroup($group);
	}))->where('id', '[0-9]+');

	Route::get('/admin/users/user/{id}', array('as' => 'admin.users.user', function($id) {
		$user = \Sentry::findUserById($id);
		return App::make('LemonTree\UsersController')->getUser($user);
	}))->where('id', '[0-9]+');

	Route::get('/admin/group/{id}', array('as' => 'admin.group', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->getEdit($group);
	}))->where('id', '[0-9]+');

	Route::get('/admin/group/{id}/items', array('as' => 'admin.group.items', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->getEditItemPermissions($group);
	}))->where('id', '[0-9]+');

	Route::get('/admin/group/{id}/elements', array('as' => 'admin.group.elements', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->getEditElementPermissions($group);
	}))->where('id', '[0-9]+');

	Route::get('/admin/group/delete/{id}', array('as' => 'admin.group.delete', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->getDelete($group);
	}))->where('id', '[0-9]+');

	Route::get('/admin/user/{id}', array('as' => 'admin.user', function($id) {
		$user = \Sentry::findUserById($id);
		return App::make('LemonTree\UserController')->getEdit($user);
	}))->where('id', '[0-9]+');

	Route::get('/admin/user/delete/{id}', array('as' => 'admin.user.delete', function($id) {
		$user = \Sentry::findUserById($id);
		return App::make('LemonTree\UserController')->getDelete($user);
	}))->where('id', '[0-9]+');

	Route::get('/admin/group/create', array('as' => 'admin.group.create', 'uses' => 'LemonTree\GroupController@getCreate'));

	Route::get('/admin/user/create', array('as' => 'admin.user.create', 'uses' => 'LemonTree\UserController@getCreate'));

	Route::get('/admin/browse', array('as' => 'admin.browse', 'uses' => 'LemonTree\BrowseController@getIndex'));

	Route::get('/admin/browse/{classId}', array('as' => 'admin.browse', function($classId) {
		try {
			$element = LemonTree\Element::getByClassId($classId);
			return App::make('LemonTree\BrowseController')->getIndex($element);
		} catch (Exception $e) {
			echo $e->getTraceAsString(); die();
			return Redirect::route('admin');
		}
	}));

	Route::get('/admin/trash/{classId?}', array('as' => 'admin.trash', function($classId = null) {
		try {
			$element = LemonTree\Element::getWithTrashedByClassId($classId);
			return App::make('LemonTree\BrowseController')->getIndex($element);
		} catch (Exception $e) {
			return Redirect::route('admin.trash');
		}
	}));

	Route::get('/admin/browse/{classId}/addtab', array('as' => 'admin.browse.addtab', function($classId) {
		try {
			$element = LemonTree\Element::getByClassId($classId);
			return App::make('LemonTree\BrowseController')->getAddTab($element);
		} catch (Exception $e) {
			return Redirect::route('admin');
		}
	}));

	Route::get('/admin/edit/{classId}', array('as' => 'admin.edit', function($classId) {
		try {
			$element = LemonTree\Element::getWithTrashedByClassId($classId);
			return App::make('LemonTree\EditController')->getEdit($element);
		} catch (Exception $e) {
			return Redirect::route('admin');
		}
	}));

	Route::get('/admin/edit/{classId}/addtab', array('as' => 'admin.edit.addtab', function($classId) {
		try {
			$element = LemonTree\Element::getByClassId($classId);
			return App::make('LemonTree\EditController')->getAddTab($element);
		} catch (Exception $e) {
			return Redirect::route('admin');
		}
	}));

	Route::get('/admin/create/{class}/{classId?}', array('as' => 'admin.create', function($class, $classId = null) {
		try {
			$element = new $class;
			$parent = LemonTree\Element::getByClassId($classId);
			return App::make('LemonTree\EditController')->getCreate($element, $parent);
		} catch (Exception $e) {
			return Redirect::route('admin');
		}
	}));

	Route::get('/admin/moving', function() {
		return \Redirect::route('admin');
	});

	Route::get('/admin/move', function() {
		return \Redirect::route('admin');
	});

	Route::get('/admin/hint/{class}', array('as' => 'admin.hint', 'uses' => 'LemonTree\HintController@getHint'));

	Route::get('/admin/multihint/{itemName}/{propertyName}', array('as' => 'admin.multihint', 'uses' => 'LemonTree\HintController@getMultiHint'));

	Route::model('tab', 'LemonTree\Tab', function() {
		return Redirect::route('admin');
	});

	Route::get('/admin/tab/{tab}', array('as' => 'admin.tab', 'uses' => 'LemonTree\TabController@getIndex'))->where('tab', '[0-9]+');

	Route::get('/admin/tab/delete/{tab}', array('as' => 'admin.tab.delete', 'uses' => 'LemonTree\TabController@getDeleteTab'))->where('tab', '[0-9]+');

	Route::get('/admin/tab/add', array('as' => 'admin.tab.add', 'uses' => 'LemonTree\TabController@getAddTab'));

});

Route::group(array('before' => 'admin.auth.post'), function() {

	Route::post('/admin/moving', array('as' => 'admin.moving', 'uses' => 'LemonTree\MoveController@postMoving'));

	Route::post('/admin/move', array('as' => 'admin.move', 'uses' => 'LemonTree\MoveController@postMove'));

});

Route::group(array('before' => 'admin.auth.ajax'), function() {

	Route::post('/admin/profile', array('as' => 'admin.profile', 'uses' => 'LemonTree\ProfileController@postUpdate'));

	Route::post('/admin/group/{id}', array('as' => 'admin.group', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->postSave($group);
	}))->where('id', '[0-9]+');

	Route::post('/admin/group/{id}/items', array('as' => 'admin.group.items', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->postSaveItemPermissions($group);
	}))->where('id', '[0-9]+');

	Route::post('/admin/group/{id}/elements', array('as' => 'admin.group.elements', function($id) {
		$group = \Sentry::findGroupById($id);
		return App::make('LemonTree\GroupController')->postSaveElementPermissions($group);
	}))->where('id', '[0-9]+');

	Route::post('/admin/user/{id}', array('as' => 'admin.user', function($id) {
		$user = \Sentry::findUserById($id);
		return App::make('LemonTree\UserController')->postSave($user);
	}))->where('id', '[0-9]+');

	Route::post('/admin/group/add', array('as' => 'admin.group.add', 'uses' => 'LemonTree\GroupController@postAdd'));

	Route::post('/admin/user/add', array('as' => 'admin.user.add', 'uses' => 'LemonTree\UserController@postAdd'));

	Route::post('/admin/browse/save', array('as' => 'admin.browse.save', 'uses' => 'LemonTree\BrowseController@postSave'));

	Route::post('/admin/browse/delete', array('as' => 'admin.browse.delete', 'uses' => 'LemonTree\BrowseController@postDelete'));

	Route::post('/admin/browse/restore', array('as' => 'admin.browse.restore', 'uses' => 'LemonTree\BrowseController@postRestore'));

	Route::post('/admin/edit/{classId}', array('as' => 'admin.save', function($classId) {
		try {
			$element = LemonTree\Element::getByClassId($classId);
			return App::make('LemonTree\EditController')->postSave($element);
		} catch (Exception $e) {
			echo $e->getMessage().PHP_EOL;
			echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
		}
	}));

	Route::post('/admin/create/{class}', array('as' => 'admin.add', function($class) {
		try {
			$element = new $class;
			return App::make('LemonTree\EditController')->postAdd($element);
		} catch (Exception $e) {
			echo $e->getMessage().PHP_EOL;
			echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
		}
	}));

	Route::post('/admin/delete/{classId}', array('as' => 'admin.delete', function($classId) {
		try {
			$element = LemonTree\Element::getWithTrashedByClassId($classId);
			if ($element->trashed()) {
				return App::make('LemonTree\EditController')->postForceDelete($element);
			} else {
				return App::make('LemonTree\EditController')->postDelete($element);
			}
		} catch (Exception $e) {
			echo $e->getMessage().PHP_EOL;
			echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
		}
	}));

	Route::post('/admin/restore/{classId}', array('as' => 'admin.restore', function($classId) {
		try {
			$element = LemonTree\Element::getOnlyTrashedByClassId($classId);
			return App::make('LemonTree\EditController')->postRestore($element);
		} catch (Exception $e) {
			echo $e->getMessage().PHP_EOL;
			echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
		}
	}));

});

Route::group(array('before' => 'admin.auth.ajax.html'), function() {

	Route::post('/admin/tree', array('as' => 'admin.tree', 'uses' => 'LemonTree\TreeController@show'));

	Route::post('/admin/tree/open', array('as' => 'admin.tree.open', 'uses' => 'LemonTree\TreeController@postOpen'));

	Route::post('/admin/tree/open1', array('as' => 'admin.tree.open1', 'uses' => 'LemonTree\TreeController@postOpen1'));

	Route::model('tab', 'LemonTree\Tab', function() {
		return null;
	});

	Route::post('/admin/tab/toggle/{tab}', array('as' => 'admin.tab.toggle', 'uses' => 'LemonTree\TabController@postToggle'))->where('tab', '[0-9]+');

	Route::post('/admin/browse/list', array('as' => 'admin.browse.list', 'uses' => 'LemonTree\BrowseController@postList'));

	Route::post('/admin/trash/list', array('as' => 'admin.trash.list', 'uses' => 'LemonTree\BrowseController@postList'));

	Route::post('/admin/search/item', array('as' => 'admin.search.item', 'uses' => 'LemonTree\SearchController@postItem'));

	Route::post('/admin/search/list', array('as' => 'admin.search.list', 'uses' => 'LemonTree\SearchController@postList'));

});

Route::post('*', function() {
	return Response::view('error404', array(), 404);
});

