<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TahunAjaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Group Utama: Dashboard Prefix & Auth Middleware
Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    // 1. Dashboard & Settings
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');

        // Settings (Group lagi biar rapi namanya jadi settings.index / settings.update)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'settings')->name('index');
            Route::put('/', 'updateSettings')->name('update');
        });
    });

    // 2. Profile Management
    // Semua URL diawali /dashboard/profile
    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        // General Profile
        Route::get('/', 'edit')->name('profile.edit');
        Route::patch('/', 'update')->name('profile.update');
        Route::delete('/', 'destroy')->name('profile.destroy');

        // Password Specific (Sub-fitur)
        Route::get('/password', 'editPassword')->name('password.edit');
        Route::patch('/password', 'updatePassword')->name('password.update');
    });

    // 3. Tahun Ajaran
    // Custom route ditaruh SEBELUM resource biar tidak tertimpa logic 'show/update' bawaan resource
    Route::patch('tahun-ajaran/{tahun_ajaran}/set-active', [TahunAjaranController::class, 'setActive'])
        ->name('tahun-ajaran.set-active');

    Route::resource('tahun-ajaran', TahunAjaranController::class);

    // 4. Mata Pelajaran
    Route::resource('mata-pelajaran', \App\Http\Controllers\MataPelajaranController::class);

    // 5. Siswa
    Route::resource('siswa', \App\Http\Controllers\SiswaController::class);
});

require __DIR__ . '/auth.php';
