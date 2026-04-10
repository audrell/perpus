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

Route::get('/errors/check/{code}', [App\Http\Controllers\ErrorTestController::class, 'checkError'])
    ->whereNumber('code')
    ->name('errors.check');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Redirect /dashboard ke home
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard.index');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/settings', [App\Http\Controllers\SettingAppController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingAppController::class, 'store'])->name('settings.store');
    Route::put('/settings/{id}', [App\Http\Controllers\SettingAppController::class, 'update'])->name('settings.update');
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('books/import/template', [App\Http\Controllers\BookController::class, 'downloadImportTemplate'])->name('books.import.template');
    Route::get('/books/export', [App\Http\Controllers\BookController::class, 'export'])->name('books.export');
    Route::post('books/import', [App\Http\Controllers\BookController::class, 'import'])->name('books.import');

    Route::get('/loans/create', [App\Http\Controllers\LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [App\Http\Controllers\LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans', [App\Http\Controllers\LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans/approve/{id}', [App\Http\Controllers\LoanController::class, 'approve'])->name('book-loans.approve');
    Route::post('/loans/reject/{id}', [\App\Http\Controllers\LoanController::class, 'reject'])->name('book-loans.reject');
    Route::get('/loans/show/{id}', [\App\Http\Controllers\LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans/return/{id}', [\App\Http\Controllers\LoanController::class, 'returnBook'])->name('book-loans.return');

    //route resource
    Route::resource('permissions', App\Http\Controllers\PermissionsController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('books', App\Http\Controllers\BookController::class);
});
