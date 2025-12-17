<?php

use Illuminate\Support\Facades\Route;
use Modules\IntentForms\App\Http\Controllers\IntentFormsController;

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
    Route::get('/intentform', 'IntentFormsController@report')->name('intentform.report');
    Route::get('/create', 'IntentFormsController@create')->name('intentform.create');
    Route::post('/store', 'IntentFormsController@store')->name('intentform.store');
    Route::get('/{id}/edit', 'IntentFormsController@edit')->name('intentform.edit');
    Route::put('/{id}/update', 'IntentFormsController@update')->name('intentform.update');
    Route::get('/{id}/show', 'IntentFormsController@show')->name('intentform.show');
    Route::get('/{id}/print', 'IntentFormsController@print')->name('intentform.print');
    Route::delete('/product/delete', 'IntentFormsController@destroy')->name('intentform.delete');

    Route::get('/type', 'IntentFormsController@type')->name('intentform.type');
    Route::get('/type/create', 'IntentFormsController@typeCreate')->name('intentform.type.create');
    Route::post('/type/store', 'IntentFormsController@typeStore')->name('intentform.type.store');
    Route::get('/type/{id}/edit', 'IntentFormsController@typeEdit')->name('intentform.type.edit');
    Route::put('/type/{id}/update', 'IntentFormsController@typeUpdate')->name('intentform.type.update');
    Route::delete('/type/{id}/delete', 'IntentFormsController@typeDestroy')->name('intentform.type.delete');
});
