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

Route::prefix('inventory')->group(function () {
    Route::get('/', 'InventoryController@index')->name('inventory');
    Route::get('/overview', 'InventoryController@overview')->name('overview');
    Route::get('/report', 'InventoryController@report')->name('report');
    Route::get('/report', 'InventoryController@report')->name('report');
    Route::get('/product/create', 'InventoryController@create')->name('product-create');
    Route::post('/product/store', 'InventoryController@store')->name('product-store');
    Route::get('/product/{id}/edit', 'InventoryController@edit')->name('product-edit');
    Route::put('/product/{id}/update', 'InventoryController@update')->name('product-update');
    Route::delete('/product/delete', 'InventoryController@destroy')->name('product-delete');
});

