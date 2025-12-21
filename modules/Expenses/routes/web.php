<?php

use Illuminate\Support\Facades\Route;
use Modules\Expenses\App\Http\Controllers\ExpensesController;

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

Route::group([], function () {
    Route::resource('expenses', ExpensesController::class)->names('expenses');
});


Route::prefix('expenses')->group(function () {
    Route::get('/', 'ExpensesController@index')->name('expenses');
    Route::get('/report', 'ExpensesController@report')->name('expenses.report');
    Route::get('/report/export', 'ExpensesController@exportReport')->name('expenses.report.export');
    Route::get('/create', 'ExpensesController@create')->name('expenses.create');
    Route::post('/store', 'ExpensesController@store')->name('expenses.store');
    Route::get('/{id}/edit', 'ExpensesController@edit')->name('expenses.edit');
    Route::put('/{id}/update', 'ExpensesController@update')->name('expenses.update');
    Route::get('/{id}/show', 'ExpensesController@show')->name('expenses.show');
});