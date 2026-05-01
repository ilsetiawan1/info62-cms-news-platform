<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('seputaradmin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
});

require __DIR__.'/auth.php';
