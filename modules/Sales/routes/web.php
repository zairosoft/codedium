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


Route::prefix('sales')->group(function () {
    Route::get('/', 'SalesController@index')->name('sales');
    Route::get('dashboard', 'SalesController@dashboard')->name('dashboard');
    Route::get('create', 'SalesController@create')->name('sales.create');
    Route::post('import', 'SalesController@import')->name('sales.import');

    Route::get('product/target', 'SalesController@target')->name('sales.product.target');
    Route::get('product/target/add', 'SalesController@targetAdd')->name('sales.product.target.add');

    Route::get('product/', 'SalesController@product')->name('sales.product');
    Route::post('product/add', 'SalesController@productAdd')->name('sales.product.add');
});
