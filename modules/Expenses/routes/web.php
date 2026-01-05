<?php

use Illuminate\Support\Facades\Route;
use Modules\Expenses\App\Http\Controllers\ExpensesController;
use Modules\Expenses\App\Http\Controllers\CategoryController;

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

Route::prefix('expenses')->group(function () {
    Route::get('/', 'ExpensesController@index')->name('expenses');
    Route::get('/report', 'ExpensesController@report')->name('expenses.report');
    Route::get('/report/export', 'ExpensesController@exportReport')->name('expenses.report.export');
    Route::get('/create', 'ExpensesController@create')->name('expenses.create');
    Route::post('/store', 'ExpensesController@store')->name('expenses.store');
    Route::get('/{id}/edit', 'ExpensesController@edit')->name('expenses.edit');
    Route::put('/{id}/update', 'ExpensesController@update')->name('expenses.update');
    Route::get('/{id}/show', 'ExpensesController@show')->name('expenses.show');
    Route::get('/{id}/print', 'ExpensesController@print')->name('expenses.print');
    Route::delete('/delete', 'ExpensesController@destroy')->name('expenses.delete');

    // Category management routes
    Route::get('/categories', 'CategoryController@index')->name('expenses.categories.index');
    Route::get('/categories/create', 'CategoryController@create')->name('expenses.categories.create');
    Route::post('/categories/store', 'CategoryController@store')->name('expenses.categories.store');
    Route::get('/categories/{id}/edit', 'CategoryController@edit')->name('expenses.categories.edit');
    Route::put('/categories/{id}/update', 'CategoryController@update')->name('expenses.categories.update');
    Route::delete('/categories/delete', 'CategoryController@destroy')->name('expenses.categories.delete');
});