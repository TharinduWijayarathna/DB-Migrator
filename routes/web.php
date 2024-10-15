<?php

use App\Http\Controllers\BaseController;
use App\Http\Controllers\DBConnectionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware('auth')->group(function () {
    Route::get('/', BaseController::class)->name('dashboard');

    Route::prefix('connection')->group(function () {
        Route::get('/', [DBConnectionController::class, 'index'])->name('connection.index');
        Route::get('/create', [DBConnectionController::class, 'create'])->name('connection.create');
        Route::post('/', [DBConnectionController::class, 'store'])->name('connection.store');
        Route::get('/{dBConnection}', [DBConnectionController::class, 'show'])->name('connection.show');
        Route::get('/{dBConnection}/edit', [DBConnectionController::class, 'edit'])->name('connection.edit');
        Route::patch('/{dBConnection}', [DBConnectionController::class, 'update'])->name('connection.update');
        Route::delete('/{dBConnection}', [DBConnectionController::class, 'destroy'])->name('connection.destroy');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});




require __DIR__ . '/auth.php';
