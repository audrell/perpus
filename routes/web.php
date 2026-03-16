<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate']);
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
});

// Redirect /dashboard ke home
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard.index');

Route::get('/errors/check/{code}', [App\Http\Controllers\ErrorTestController::class, 'checkError'])
    ->whereNumber('code')
    ->name('errors.check');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/settings', [App\Http\Controllers\SettingAppController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingAppController::class, 'store'])->name('settings.store');
    Route::put('/settings/{id}', [App\Http\Controllers\SettingAppController::class, 'update'])->name('settings.update');

    Route::resource('permissions', App\Http\Controllers\PermissionsController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});
