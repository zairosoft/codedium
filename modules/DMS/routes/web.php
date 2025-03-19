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


Route::prefix('dms')->group(function () {
    Route::get('/', 'DMSController@index')->name('dms');
    Route::get('/overview', 'DMSController@overview')->name('dms.overview');
    Route::get('/monitor', 'DMSController@monitor')->name('dms.monitor');
    Route::get('/{id}/ajax', 'DMSController@ajax')->name('dms.ajax');
    Route::get('/{id}/show', 'DMSController@show')->name('dms.show');
    Route::get('/create', 'DMSController@create')->name('dms.create');
    Route::post('/store', 'DMSController@store')->name('dms.store');
    Route::get('/{id}/edit', 'DMSController@edit')->name('dms.edit');
    Route::post('/update', 'DMSController@update')->name('dms.update');
    Route::get('/history', 'DMSController@history')->name('dms.history');
    Route::get('/{id}/log', 'DMSController@log')->name('dms.log');
    Route::delete('/delete', 'DMSController@destroy')->name('dms.delete');
});
