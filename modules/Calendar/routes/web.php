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

Route::prefix('calendar')->group(function () {
    Route::get('/', 'CalendarController@index')->name('calendar');
    Route::post('/store', 'CalendarController@store')->name('calendar.store');
    Route::put('/update', 'CalendarController@update')->name('calendar.update');
    Route::post('/delete', 'CalendarController@destroy')->name('calendar.delete');
});
