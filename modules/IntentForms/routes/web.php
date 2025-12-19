<?php

use Illuminate\Support\Facades\Route;
use Modules\IntentForms\App\Http\Controllers\IntentFormsController;
use Modules\IntentForms\App\Http\Controllers\TypeController;

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

Route::prefix('intentform')->group(function () {
    Route::get('/', 'IntentFormsController@index')->name('intentform');
    Route::get('/report', 'IntentFormsController@report')->name('intentform.report');
    Route::get('/report/export', 'IntentFormsController@exportReport')->name('intentform.report.export');
    Route::get('/create', 'IntentFormsController@create')->name('intentform.create');
    Route::post('/store', 'IntentFormsController@store')->name('intentform.store');
    Route::get('/{id}/edit', 'IntentFormsController@edit')->name('intentform.edit');
    Route::put('/{id}/update', 'IntentFormsController@update')->name('intentform.update');
    Route::get('/{id}/show', 'IntentFormsController@show')->name('intentform.show');
    Route::get('/{id}/print', 'IntentFormsController@print')->name('intentform.print');
    Route::delete('/product/delete', 'IntentFormsController@destroy')->name('intentform.delete');

    // Type management routes
    Route::get('/types', 'TypeController@index')->name('intentform.types.index');
    Route::get('/types/create', 'TypeController@create')->name('intentform.types.create');
    Route::post('/types/store', 'TypeController@store')->name('intentform.types.store');
    Route::get('/types/{id}/edit', 'TypeController@edit')->name('intentform.types.edit');
    Route::put('/types/{id}/update', 'TypeController@update')->name('intentform.types.update');
    Route::delete('/types/delete', 'TypeController@destroy')->name('intentform.types.delete');
});
