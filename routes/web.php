<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//https://lokalise.com/blog/laravel-localization-step-by-step/
Route::get('language/{locale}', function ($locale) {
    // app()->setLocale($locale);
    // session()->put('locale', $locale);
    //return redirect()->back();
    if (Cookie::has('locale')) {
        Cookie::queue(Cookie::forget('locale'));
    }
    Cookie::queue("locale", $locale, 2628000);
    return redirect()->back();
});

Route::get('/', function ($locale = null) {
    return redirect('/auth/login');
})->name('main');

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/auth/login');
});

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::get('/auth/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('auth/forgot/password', 'forgot')->name('forgot');
    Route::post('auth/forgot/password', 'forgotpassword')->name('forgot.password');
    Route::get('auth/check/email', 'check')->name('checkemail');
    Route::get('/auth/reset/{token}', 'reset')->name('reset');
    Route::post('/auth/reset/{token}', 'resetpassword')->name('reset.password');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(App\Http\Controllers\LockScreenController::class)->group(function () {
    Route::get('/lockscreen', 'lockscreen')->name('lockscreen');
    Route::post('/lockscreen/authenticate', 'authenticate')->name('lockscreen.authenticate');
});

// Route for Permissions & Roles
Route::group(['middleware' => ['role:super-admin|admin']], function () {
    // Permissions
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{id}/show', [App\Http\Controllers\PermissionController::class, 'view'])->name('permission.show');
    Route::delete('permissions/delete', [App\Http\Controllers\PermissionController::class, 'destroy'])->name('permission.delete');

    // Roles
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{id}/show', [App\Http\Controllers\RoleController::class, 'show'])->name('role.show');
    Route::delete('roles/delete', [App\Http\Controllers\RoleController::class, 'destroy'])->name('role.delete');

    // Users
    Route::resource('users', App\Http\Controllers\UsersController::class);
    Route::get('users/{id}/show', [App\Http\Controllers\UsersController::class, 'show'])->name('user.show');
    Route::get('users/show', [App\Http\Controllers\UsersController::class, 'show'])->name('user.show');
    Route::delete('user/delete', [App\Http\Controllers\UsersController::class, 'destroy'])->name('user.delete');

    // Settings
    Route::get('settings/general', [App\Http\Controllers\SettingsController::class, 'general'])->name('setting.general');
    Route::post('general/update', [App\Http\Controllers\SettingsController::class, 'updateGeneral'])->name('setting.general.update');

    Route::get('settings/company', [App\Http\Controllers\SettingsController::class, 'company'])->name('setting.company');
    Route::get('settings/company/{id}/edit', [App\Http\Controllers\SettingsController::class, 'companyEdit'])->name('setting.company.edit');
    Route::get('settings/company/{id}/show', [App\Http\Controllers\SettingsController::class, 'companyView']);
    Route::get('settings/company/create', [App\Http\Controllers\SettingsController::class, 'companyCreate'])->name('setting.company.create');
    Route::post('settings/company/store', [App\Http\Controllers\SettingsController::class, 'companyStore'])->name('setting.company.store');
    Route::post('settings/company/update', [App\Http\Controllers\SettingsController::class, 'companyUpdate'])->name('setting.company.update');
    Route::delete('settings/company/delete', [App\Http\Controllers\SettingsController::class, 'companyDestroy'])->name('setting.company.delete');

    // Apps
    Route::get('apps', [App\Http\Controllers\AppsController::class, 'index'])->name('apps');
    Route::get('app/{id}/download', [App\Http\Controllers\AppsController::class, 'download'])->name('app.download');
    Route::get('app/{id}/install', [App\Http\Controllers\AppsController::class, 'install'])->name('app.install');
    Route::get('app/{id}/uninstall', [App\Http\Controllers\AppsController::class, 'unInstall'])->name('app.uninstall');
});

Route::controller(App\Http\Controllers\ProfileController::class)->group(function () {
    Route::get('/profile', 'profile')->name('profile');
    Route::get('/profile/edit', 'edit')->name('profileedit');
    Route::post('/profile/update', 'update')->name('profileupdate');
    Route::post('/profile/reset/password', 'resetpassword')->name('profile.reset.password');
});

// Route for notifications
Route::get('notifications', [App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications');
Route::get('notifications/show', [App\Http\Controllers\NotificationsController::class, 'show'])->name('notifications.show');
Route::post('notifications/delete', [App\Http\Controllers\NotificationsController::class, 'delete'])->name('notifications.delete');

// Route for support
// Route::get('/support', function () {
//     return view('welcome');
// })->name('support');
