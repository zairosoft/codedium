<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\CategoryController;
use Modules\Inventory\Http\Controllers\ReportController;

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
    // Inventory/Products routes
    Route::get('/', [InventoryController::class, 'index'])->name('inventory');
    Route::get('/overview', [InventoryController::class, 'overview'])->name('inventory.overview');
    Route::get('/report', [InventoryController::class, 'report'])->name('inventory.report');

    Route::get('/product/create', [InventoryController::class, 'create'])->name('product-create');
    Route::post('/product/store', [InventoryController::class, 'store'])->name('product.store');
    Route::get('/product/{id}', [InventoryController::class, 'show'])->name('product.show');
    Route::get('/product/{id}/edit', [InventoryController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}/update', [InventoryController::class, 'update'])->name('product.update');
    Route::delete('/product/delete', [InventoryController::class, 'destroy'])->name('product.delete');

    // Category routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/delete', [CategoryController::class, 'destroy'])->name('category.delete');

    // Inventory Report Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('inventory.reports');
    Route::get('/reports/csv', [ReportController::class, 'exportCSV'])->name('inventory.reports.csv');
});

