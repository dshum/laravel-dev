<?php

Route::get('/admin', array('as' => 'admin', 'uses' => 'LemonTree\MainController@getIndex'));

Route::get('/admin/browse', array('as' => 'admin.browse', 'uses' => 'LemonTree\MainController@getIndex'));

Route::get('/admin/login', array('as' => 'admin.login', 'uses' => 'LemonTree\LoginController@getIndex'));
Route::post('/admin/login', array('as' => 'admin.login', 'uses' => 'LemonTree\LoginController@postLogin'));

Route::get('/admin/logout', array('as' => 'admin.logout', 'uses' => 'LemonTree\LoginController@getLogout'));

Route::get('/admin/restore', array('as' => 'admin.restore', 'uses' => 'LemonTree\RestoreController@getIndex'));
Route::post('/admin/restore', array('as' => 'admin.restore', 'uses' => 'LemonTree\RestoreController@postRestore'));

Route::get('/admin/reset', array('as' => 'admin.reset', 'uses' => 'LemonTree\RestoreController@getReset'));
Route::post('/admin/reset', array('as' => 'admin.reset', 'uses' => 'LemonTree\RestoreController@postReset'));

Route::get('/admin/profile', array('as' => 'admin.profile', 'uses' => 'LemonTree\ProfileController@getIndex'));
Route::post('/admin/profile', array('as' => 'admin.profile', 'uses' => 'LemonTree\ProfileController@postUpdate'));

Route::get('/admin/search', array('as' => 'admin.search', 'uses' => 'LemonTree\SearchController@getIndex'));

Route::get('/admin/trash', array('as' => 'admin.trash', 'uses' => 'LemonTree\TrashController@getIndex'));

Route::get('/admin/users', array('as' => 'admin.users', 'uses' => 'LemonTree\UsersController@getIndex'));

Route::get('/admin/group/{id}', array('as' => 'admin.group', function($id) {
	$group = Group::find($id);
	return App::make('LemonTree\GroupController')->getIndex($group);
}));

Route::post('/admin/tree/open', array('as' => 'admin.tree.open', 'uses' => 'LemonTree\TreeController@postOpen'));

Route::get('/admin/browse/{class}.{id}', array('as' => 'admin.browse', function($class, $id) {
	$element = $class::find($id);
	return App::make('LemonTree\MainController')->getIndex($element);
}));

Route::post('/admin/list/{class}/{pclass?}.{pid?}', array('as' => 'admin.list', function($class, $pclass = null, $pid = null) {
	try {
		$parent = $pclass::find($pid);
		return App::make('LemonTree\MainController')->postList($class, $parent);
	} catch (\Exception $e) {
		echo $e->getMessage().PHP_EOL;
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
	}
}));

Route::get('/admin/edit/{class}.{id}', array('as' => 'admin.edit', function($class, $id) {
	try {
		$element =
			$class::withTrashed()->
			cacheTags($class)->
			rememberForever()->
			find($id);
		return App::make('LemonTree\EditController')->getEdit($element);
	} catch (\Exception $e) {
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
		return Redirect::route('admin');
	}
}));

Route::post('/admin/edit/{class}.{id}', array('as' => 'admin.save', function($class, $id) {
	try {
		$element =
			$class::withTrashed()->
			cacheTags($class)->
			rememberForever()->
			find($id);
		return App::make('LemonTree\EditController')->postSave($element);
	} catch (\Exception $e) {
		echo $e->getMessage().PHP_EOL;
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
	}
}));

Route::get('/admin/create/{class}/{pclass?}.{pid?}', array('as' => 'admin.create', function($class, $pclass = null, $pid = null) {
	try {
		$element = new $class;
		$parent = $pclass && $pid ? $pclass::find($pid) : null;
		return App::make('LemonTree\EditController')->getCreate($element, $parent);
	} catch (\Exception $e) {
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
		return Redirect::route('admin');
	}
}));

Route::post('/admin/create/{class}', array('as' => 'admin.add', function($class) {
	try {
		$element = new $class;
		return App::make('LemonTree\EditController')->postAdd($element);
	} catch (\Exception $e) {
		echo $e->getMessage().PHP_EOL;
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
	}
}));

Route::post('/admin/delete/{class}.{id}', array('as' => 'admin.delete', function($class, $id) {
	try {
		$element =
			$class::withTrashed()->
			cacheTags($class)->
			rememberForever()->
			find($id);
		if ($element->trashed()) {
			return App::make('LemonTree\EditController')->postForceDelete($element);
		} else {
			return App::make('LemonTree\EditController')->postDelete($element);
		}
	} catch (\Exception $e) {
		echo $e->getMessage().PHP_EOL;
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
	}
}));

Route::post('/admin/restore/{class}.{id}', array('as' => 'admin.restore', function($class, $id) {
	try {
		$element =
			$class::onlyTrashed()->
			cacheTags($class)->
			rememberForever()->
			find($id);
		return App::make('LemonTree\EditController')->postRestore($element);
	} catch (\Exception $e) {
		echo $e->getMessage().PHP_EOL;
		echo '<pre>'.$e->getTraceAsString().'</pre>'; die();
	}
}));

Route::get('/admin/hint/{class}', array('as' => 'admin.hint', 'uses' => 'LemonTree\EditController@getHint'));

Route::get('/admin/trash/{class?}.{id?}', array('as' => 'admin.trash', function($class = null, $id = null) {
	try {
		$element =
			$class::onlyTrashed()->
			cacheTags($class)->
			rememberForever()->
			find($id);
		return App::make('LemonTree\TrashController')->getIndex($element);
	} catch (\Exception $e) {
		return Redirect::route('admin.trash');
	}
}));
