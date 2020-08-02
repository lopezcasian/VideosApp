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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function(){

	//Video routes
	Route::view('/videos/create','video.create')
		->name('videos.create');

	Route::resource('videos', 'VideoController')->except([
		'index','show', 'create'
	]);

	// Comments routes
	Route::resource('comment', 'CommentController')->only([
		'store', 'destroy'
	]);

});


Route::get('/profile/{image?}', array(
	'as' => 'profileImage',
	'uses' => 'UserController@getProfileImage'
));

Route::resource('users', 'UserController')->only([
    'show'
]);

Route::get('/videos/{video}', array(
		'as' => 'videos.show',
		'uses' => 'VideoController@show'
	));

Route::get('/videos/miniature/{filename}', array(
		'as' => 'videos.get.miniature',
		'uses' => 'VideoController@getImage'
	));

Route::get('/videos/file/{filename}', array(
		'as' => 'videos.get.video',
		'uses' => 'VideoController@getVideo'
	));

Route::post('/videos/search', [
		'as' => 'video.search',
		'uses' => 'VideoController@search'
	]);

//cache
Route::get('/clear-cache', function(){
	$code = Artisan::call('cache:clear');
});
