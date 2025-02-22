<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{EmployeController, CategoryController, ItemController, DataController, ReportController,CustomerController};

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
    Route::resource('/employe', EmployeController::class);
    Route::put('/employe/{employe}/updateStatus', [EmployeController::class, 'updateStatus'])->name('employe.updateStatus');

    Route::resource('/items', ItemController::class);
    Route::put('/items/{item}/updateStatus', [ItemController::class, 'updateStatus'])->name('items.updateStatus');
    Route::post('/items/{item}/replicate', [ItemController::class, 'replicate'])->name('items.replicate');


    //caetgory
    Route::resource('/category', CategoryController::class);
    Route::put('/category/{category}/updateStatus', [CategoryController::class, 'updateStatus'])->name('category.updateStatus');

    //customer resource
    Route::resource('/customer', CustomerController::class);
    Route::put('/customer/{customer}/updateStatus', [CustomerController::class, 'updateStatus'])->name('customer.updateStatus');
});

// Rutas para Registrador usando el alias 'registrador'
Route::middleware(['auth', 'registrador'])->group(function () {
    Route::resource('/data', DataController::class);
    Route::put('/data/{data}/updateStatus', [DataController::class, 'updateStatus'])->name('data.updateStatus');
    Route::post('/items/searchName', [ItemController::class, 'searchName'])->name('items.searchName');
});

// Rutas protegidas para admin o registrador
Route::middleware(['auth', 'admin_or_registrador'])->group(function () {
    Route::resource('/data', DataController::class);
    Route::put('/data/{data}/updateStatus', [DataController::class, 'updateStatus'])->name('data.updateStatus');
    Route::post('/items/searchName', [ItemController::class, 'searchName'])->name('items.searchName');
});


Route::get('/reporte-semanal/{id}', [ReportController::class, 'showReport'])->name('report.show');



require __DIR__.'/auth.php';

