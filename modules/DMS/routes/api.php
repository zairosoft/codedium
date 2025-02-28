<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\DMS\App\Http\Controllers\ApiDMSController;

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

Route::middleware(['web','auth:api'])->prefix('v1')->name('api.')->group(function () {
    Route::get('dms', [ApiDMSController::class, 'index']);
    Route::post('dms/alarm', [ApiDMSController::class, 'store']);
});
