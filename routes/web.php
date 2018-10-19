<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();


Route::middleware(['auth'])->group(function(){

	//Video routes
	Route::view('/createvideo','video.createVideo')
		->name('createVideo');

	Route::resource('videos', 'VideoController')->except([
		'index','show'
	]);

	// Comments routes
	Route::resource('comment', 'CommentController')->only([
		'store', 'destroy'
	]);

});


Route::get('/', 'HomeController@index')->name('home');


Route::get('/thumbnail/{filename}', array(
		'as' => 'imageVideo',
		'uses' => 'VideoController@getImage'
	));

Route::get('/video/{video_id}', array(
		'as' => 'detailVideo',
		'uses' => 'VideoController@getVideoDetail'
	));

Route::get('/video-file/{filename}', array(
		'as' => 'fileVideo',
		'uses' => 'VideoController@getVideo'
	));

Route::get('/search/{search?}/{filter?}', [
		'as' => 'videoSearch',
		'uses' => 'VideoController@search'
	]);

//cache

Route::get('/clear-cache', function(){
	$code = Artisan::call('cache:clear');
});

//users
Route::get('/channel/{user_id}', array(
		'as' => 'channel',
		'uses' => 'UserController@channel'
	));