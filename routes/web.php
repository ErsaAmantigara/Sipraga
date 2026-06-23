<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaSawController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengerjaanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PenilaianSawController;
use App\Http\Controllers\ProfilePelangganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'isActive', 'cabangIsActive', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'isActive', 'cabangIsActive'])->group(function () {
    // Profile
        Route::get('/profile', [ProfilePelangganController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfilePelangganController::class, 'update'])->name('profile.update');

    // Users
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

    // Roles
    Route::resource('roles', RoleController::class)->except(['create', 'store']);
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    
    // Cabin/Cabang
    Route::resource('cabang', CabangController::class);

    // Kriteria (SAW)
    Route::resource('kriteria-saw', KriteriaSawController::class)->except(['create', 'store', 'destroy']);

    // Pengaduan
    Route::resource('pengaduan', PengaduanController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/pengaduan/{pengaduan}/validate', [PengaduanController::class, 'validasi'])->name('pengaduan.validate');
    Route::post('/pengaduan/{pengaduan}/assign-teknisi', [PengaduanController::class, 'assignTeknisi'])->name('pengaduan.assign-teknisi');

    // Penilaian SAW
    Route::get('/penilaian-saw', [PenilaianSawController::class, 'index'])->name('penilaian-saw.index');
    Route::post('/penilaian-saw/generate', [PenilaianSawController::class, 'generate'])->name('penilaian-saw.generate');

    // Pengerjaan
    Route::resource('pengerjaan', PengerjaanController::class)->except(['create', 'store', 'destroy']);
    Route::post('/pengerjaan/{pengerjaan}/rating', [PengerjaanController::class, 'rating'])->name('pengerjaan.rating');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pengaduan', [LaporanController::class, 'pengaduan'])->name('laporan.pengaduan');
    Route::get('/laporan/pengerjaan', [LaporanController::class, 'pengerjaan'])->name('laporan.pengerjaan');


});

require __DIR__.'/auth.php';
