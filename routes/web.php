<?php

use Illuminate\Support\Facades\Route;

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
    return view('starting_page');
});

Route::get('/info', function () {
    return view('info');
});

Route::get('hobby');

Route::resource('hobby', 'HobbyController'); // 1st param = URI, 2nd = Controller; Resources created with $php artisan route:list --name=hobby
Route::resource('tag', 'TagController'); 
Route::resource('user', 'UserController'); 

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// filtered
Route::get('/hobby/tag/{tag_id}', 'HobbyTagController@getFilteredHobbies')->name('hobby_tag');
// attach and detach tags to hobbies
Route::get('/home/{hobby_id}/tag/{tag_id}/attach', 'HobbyTagController@attachTag');
Route::get('/home/{hobby_id}/tag/{tag_id}/detach', 'HobbyTagController@detachTag');
