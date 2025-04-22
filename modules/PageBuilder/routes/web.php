<?php

use Illuminate\Support\Facades\Route;
use Modules\PageBuilder\App\Http\Controllers\PageBuilderController;

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
    Route::resource('pagebuilder', PageBuilderController::class)->names('pagebuilder');
    
    // Additional routes for the page builder
    Route::get('pagebuilder/{id}/builder', [PageBuilderController::class, 'builder'])->name('pagebuilder.builder');
    Route::post('pagebuilder/{id}/save-builder', [PageBuilderController::class, 'saveBuilder'])->name('pagebuilder.save-builder');
    Route::get('page/{slug}', [PageBuilderController::class, 'preview'])->name('pagebuilder.preview');
    
    // Special route for iframe content in the builder
    Route::get('pagebuilder/{id}/iframe1', [PageBuilderController::class, 'iframeContent'])->name('pagebuilder.iframe');
});
