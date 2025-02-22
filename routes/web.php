<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{ CategoryController, UsersController};

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para Admin usando el alias 'admin'
Route::middleware(['auth', 'admin'])->group(function () {

    //caetgory
    Route::resource('/category', CategoryController::class);
    Route::put('/category/{category}/updateStatus', [CategoryController::class, 'updateStatus'])->name('category.updateStatus');

    //users resource
    Route::resource('/users', UsersController::class);
    Route::put('/users/updateStatus/status', [UsersController::class, 'updateStatus'])->name('users.updateStatus');
});

// Rutas para Registrador usando el alias 'registrador'
Route::middleware(['auth', 'entrada'])->group(function () {

});

// Rutas protegidas para admin o registrador
Route::middleware(['auth', 'admin_or_registrador'])->group(function () {

});

// rutas para salida
Route::middleware(['auth', 'salida'])->group(function () {

});




require __DIR__.'/auth.php';

