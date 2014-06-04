<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', array('as' => 'firstpage', 'uses' => 'FirstpageController@getIndex'));

Route::get('/{section}', array('as' => 'section', function($url) {

	$section =
		Section::where('url', $url)->
		cacheTags('Section')->rememberForever()->
		first();

	if (!$section) App::abort(404);

	View::share('currentElement', $section);

	switch ($url) :
		default: $controller = 'CommonController'; break;
	endswitch;

	return App::make($controller)->getIndex($section);

}));
