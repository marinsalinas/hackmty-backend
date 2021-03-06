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

Route::get('/', function()
{
	return View::make('hello');
});



//Rutas Para API RESTFUL
    Route::group(array('prefix' => 'api/v1'), function () {
        Route::resource('events', 'EventsController');
        Route::resource('search', 'SearchController');

        Route::resource('items', 'ItemsController');
    });
