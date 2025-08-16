<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('/products/export/csv', [ProductController::class, 'exportCsv'])->name('products.export.csv');


    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export/excel', [OrderController::class, 'exportExcel'])->name('orders.export.excel');
    Route::get('/orders/export/csv', [OrderController::class, 'exportCsv'])->name('orders.export.csv');
});

require __DIR__ . '/auth.php';
