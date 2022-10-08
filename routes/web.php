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
    return view('auth/login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    // Authentication Routes...
    Route::get('/home', 'HomeController@index')->name('home');

    // movies route
    Route::group(['as' => 'movies.','prefix' => 'movies/'], function () {
        Route::get('/','MovieController@index')->name('index');
        Route::post('/show-movie','MovieController@showMovie')->name('show-movie');
        Route::post('/edit','MovieController@edit')->name('edit');
        Route::post('/update','MovieController@update')->name('update');
        Route::post('/destroy','MovieController@destroy')->name('delete');
        Route::post('/load-movie-data','MovieController@loadMovie')->name('load');
        Route::post('/import','MovieController@import')->name('import');
        Route::post('/rent','MovieController@rent')->name('rent');
    });
});


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    return "Cache is cleared";
});

