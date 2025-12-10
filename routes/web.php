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
        Route::get('/password', 'editPassword')->name('profile.password.edit');
        Route::patch('/password', 'updatePassword')->name('profile.password.update');
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

    // 6. Kelas
    Route::get('kelas', [\App\Http\Controllers\KelasController::class, 'index'])->name('kelas.index');
    Route::get('kelas/create', [\App\Http\Controllers\KelasController::class, 'create'])->name('kelas.create');
    Route::post('kelas', [\App\Http\Controllers\KelasController::class, 'store'])->name('kelas.store');
    Route::get('kelas/{kelas}', [\App\Http\Controllers\KelasController::class, 'show'])->name('kelas.show');
    Route::get('kelas/{kelas}/edit', [\App\Http\Controllers\KelasController::class, 'edit'])->name('kelas.edit');
    Route::match(['put', 'patch'], 'kelas/{kelas}', [\App\Http\Controllers\KelasController::class, 'update'])->name('kelas.update');
    Route::delete('kelas/{kelas}', [\App\Http\Controllers\KelasController::class, 'destroy'])->name('kelas.destroy');

    // Rombel (Plotting Siswa ke Kelas)
    Route::post('kelas/{kelas}/siswa', [\App\Http\Controllers\KelasController::class, 'addSiswa'])->name('kelas.add-siswa');
    Route::delete('kelas/{kelas}/siswa/{kelasSiswa}', [\App\Http\Controllers\KelasController::class, 'removeSiswa'])->name('kelas.remove-siswa');

    // 7. Penugasan Mengajar
    Route::resource('penugasan-mengajar', \App\Http\Controllers\PenugasanMengajarController::class);

    // 8. Presensi Mapel & Jurnal Mengajar
    Route::controller(\App\Http\Controllers\PresensiMapelController::class)->prefix('presensi-mapel')->name('presensi-mapel.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/jurnal', 'jurnal')->name('jurnal');
    });

    // 9. Promotion and Graduation
    Route::controller(\App\Http\Controllers\PromotionController::class)->prefix('promotion')->name('promotion.')->group(function () {
        Route::get('/', 'promotionForm')->name('form');
        Route::get('/results', 'results')->name('results');
        Route::post('/students', 'getStudentsForPromotion')->name('students');
        Route::post('/promote', 'promote')->name('promote');

        // Wali Kelas Promotion Routes
        Route::get('/wali-kelas', 'waliKelasPromotionForm')->name('wali-kelas-form');
        Route::post('/wali-kelas-promote', 'waliKelasPromote')->name('wali-kelas-promote');
    });


    //graduated students
    Route::controller(\App\Http\Controllers\PromotionController::class)->prefix('graduation')->name('graduation.')->group(function () {
        Route::get('/', 'graduationForm')->name('form');
        Route::get('/results', 'results')->name('results');
        Route::post('/students', 'getStudentsForGraduation')->name('students');
        Route::post('/graduate', 'graduate')->name('graduate');
    });

    // 10. Nilai Management
    Route::controller(\App\Http\Controllers\NilaiController::class)->prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/penugasan/{penugasanId}', 'showByPenugasan')->name('show-by-penugasan');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{komponenNilaiId}', 'show')->name('show');
        Route::get('/{komponenNilaiId}/edit', 'edit')->name('edit');
        Route::match(['put', 'patch'], '/{komponenNilaiId}', 'update')->name('update');
        Route::delete('/{komponenNilaiId}', 'destroy')->name('destroy');

        // Store nilai siswa massal
        Route::post('/{komponenNilaiId}/nilai-siswa', 'storeNilaiSiswa')->name('store-nilai-siswa');
    });

    // 11. Nilai Akhir
    Route::controller(\App\Http\Controllers\NilaiAkhirController::class)->prefix('nilai-akhir')->name('nilai-akhir.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
        Route::get('/rekap-wali-kelas', 'rekapWaliKelas')->name('rekap-wali-kelas');
    });
});

require __DIR__ . '/auth.php';
