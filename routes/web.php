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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

//Route::resource('/threads', 'ThreadController');

Route::get('/threads', 'ThreadController@index')->name('threads');

Route::get('/threads/create', 'ThreadController@create');

//Route::get('/threads/search', 'SearchController@show');

Route::get('/threads/{channel}', 'ThreadController@index');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show');

Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');

Route::post('/locked-threads/{thread}', 'LockedThreadController@store')->name('locked-threads.store')->middleware('admin');

Route::delete('/locked-threads/{thread}', 'LockedThreadController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::post('/threads', 'ThreadController@store')->middleware('must-be-confirmed');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');

//middleware('throttle:1') pode ser usado para não permitir requisições em menos de 1 minuto de intervalo, porém a validação atrapalha o funcionamento
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');

Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');

Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');


Route::patch('/replies/{reply}', 'ReplyController@update');

Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-reply.store');


Route::post('/replies/{reply}/favorites', 'FavoriteController@store');

Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');


Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');

Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');

Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');


Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
