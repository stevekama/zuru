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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('to_admin');

Route::group(['namespace'=>'Backend','prefix'=>'administration','as'=>'backend.','middleware'=>'admin'],function (){
   Route::get('/','HomeController@index')->name('home');

   #Group all users routes
   Route::group([],function (){
       Route::get('users/{login_mode}','UsersController@list')->name('users');
   });

   #Group all sales routes

});