<?php

use Illuminate\Support\Facades\Route;

use Nakornsoft\PageBuilder\App\Http\Controllers\PageBuilderController;


Route::get('pagebuilder', [PageBuilderController::class, 'index'])->name('page.builder');
