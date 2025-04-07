<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->prefix('inventory')->group(function () {
    // Products API endpoints
    Route::get('products', [InventoryController::class, 'index']);
    Route::post('products', [InventoryController::class, 'store']);
    Route::get('products/{id}', [InventoryController::class, 'show']);
    Route::put('products/{id}', [InventoryController::class, 'update']);
    Route::delete('products/{id}', [InventoryController::class, 'destroy']);

    // Categories API endpoints
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
});
