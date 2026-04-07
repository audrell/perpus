<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ErrorTestController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;





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

    Route::resource('categories', CategoryController::class);


    // excel

Route::middleware(['auth'])->group(function () {

    // ===== ROUTE 1: DOWNLOAD TEMPLATE =====
    // Method: GET
    // URL: /books/import/template
    // Controller Method: downloadImportTemplate()
    Route::get(
        'books/import/template',
        [App\Http\Controllers\BookController::class, 'downloadImportTemplate']
    )->name('books.import.template');

     Route::get('/books/export', [App\Http\Controllers\BookController::class, 'export'])->name('books.export');

    // ===== ROUTE 2: UPLOAD FILE =====
    // Method: POST
    // URL: /books/import
    // Body: form-data dengan "import_file"
    // Controller Method: import()
    Route::post(
        'books/import',
        [App\Http\Controllers\BookController::class, 'import']
    )->name('books.import');

    // ===== ROUTE 3: CRUD BOOKS (Standard Resource) =====
    Route::resource('books', App\Http\Controllers\BookController::class);

    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');


});

