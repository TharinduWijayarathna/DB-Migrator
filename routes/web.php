<?php

use App\Http\Controllers\BackupRestoreController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\DBConnectionController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware('auth')->group(function () {
    Route::get('/', BaseController::class)->name('dashboard');

    Route::prefix('connection')->group(function () {
        Route::get('/', [DBConnectionController::class, 'index'])->name('connection.index');
        Route::post('/', [DBConnectionController::class, 'store'])->name('connection.store');
        Route::get('/all', [DBConnectionController::class, 'all'])->name('connection.all');
        Route::get('/{dBConnection}', [DBConnectionController::class, 'show'])->name('connection.show');
        Route::get('/{dBConnection}/edit', [DBConnectionController::class, 'edit'])->name('connection.edit');
        Route::patch('/{dBConnection}', [DBConnectionController::class, 'update'])->name('connection.update');
        Route::delete('/{dBConnection}', [DBConnectionController::class, 'destroy'])->name('connection.destroy');
        Route::patch('/{dBConnection}/activate', [DBConnectionController::class, 'activate'])->name('connection.activate');
        Route::patch('/{dBConnection}/deactivate', [DBConnectionController::class, 'deactivate'])->name('connection.deactivate');
    });

    Route::prefix('query')->group(function () {
        Route::get('/', [QueryController::class, 'index'])->name('query.index');
        Route::post('/', [QueryController::class, 'run'])->name('query.run');
    });

    Route::prefix('backup_restore')->group(function () {
        Route::get('/', [BackupRestoreController::class, 'index'])->name('backup_restore.index');
        Route::post('/restore', [BackupRestoreController::class, 'restore'])->name('backup_restore.restore');
        Route::get('/tables', [BackupRestoreController::class, 'getDatabaseTableNames'])->name('backup_restore.tables');
        Route::get('/full_backup', [BackupRestoreController::class, 'fullBackup'])->name('backup_restore.full_backup');
        Route::get('/full_backup/no_data', [BackupRestoreController::class, 'fullBackupNoData'])->name('backup_restore.full_backup_no_data');
        Route::get('/selective_backup', [BackupRestoreController::class, 'selectiveBackup'])->name('backup_restore.selective_backup');
        Route::get('/selective_backup/no_data', [BackupRestoreController::class, 'selectiveBackupNoData'])->name('backup_restore.selective_backup_no_data');
        Route::delete('/delete_backup', [BackupRestoreController::class, 'deleteBackup'])->name('backup_restore.delete_backup');
        Route::post('/restore', [BackupRestoreController::class, 'restore'])->name('backup_restore.restore');
    });

    Route::prefix('excel')->group(function () {
        Route::get('/', [ExcelController::class, 'index'])->name('excel.index');
        Route::post('/import', [ExcelController::class, 'import'])->name('excel.import');
        Route::get('/export', [ExcelController::class, 'export'])->name('excel.export');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});




require __DIR__ . '/auth.php';
