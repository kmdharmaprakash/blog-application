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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=> 'userlogin'],function(){

Route::get('/post','PostController@post');
Route::post('/addpost','PostController@addPost');

Route::get('/category' , 'CategoryController@category');
Route::post('/addcategory','CategoryController@addCategory');

Route::get('/profile' , 'ProfileController@profile');
Route::post('/addprofile','ProfileController@addProfile');


Route::get('/view/{id}','PostController@view');
Route::get('/edit/{id}','PostController@edit');
Route::post('/editpost/{id}','PostController@update');
Route::get('/delete/{id}','PostController@deletePost');

Route::get('/category/{id}','PostController@category');

Route::get('/like/{id}','PostController@like');
Route::get('/dislike/{id}','PostController@dislike');




});


