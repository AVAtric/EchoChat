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


Route::get('/', 'StartController@index')->name('welcome');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/chat', 'HomeController@chat')->name('chat');

Route::post('/verify', 'HomeController@verify')->name('verify');

Route::post('/chat', 'MessageController@sendMessageToMainChat')->name('main-message');
Route::post('/chat/{chat}', 'MessageController@sendMessageToPersonalChat')->name('personal-message');

Route::get('/new/chat/{user1}/{user2}', 'MessageController@getChatId')->name('chat-register');

Route::get('/messages', 'MessageController@getAllMainChatMessages')->name('main-messages');
Route::get('/messages/{chat}', 'MessageController@getAllChatMessages')->name('personal-messages');

Route::get('/notification/{user}', 'MessageController@sendNotification')->name('notifications');

Route::get('/quote', 'MessageController@getQuote')->name('quote');
