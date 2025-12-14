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
    Route::put('/{id}/print', 'IntentFormsController@print')->name('intentform.print');
    Route::delete('/product/delete', 'IntentFormsController@destroy')->name('intentform.delete');
});
