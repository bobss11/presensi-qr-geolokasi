<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenPertemuanController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'dosen'
            ? redirect()->route('dosen.dashboard')
            : redirect()->route('mahasiswa.scan');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenPertemuanController::class, 'index'])->name('dashboard');
    Route::post('/jadwal/{jadwalKuliah}/pertemuan', [DosenPertemuanController::class, 'storePertemuan'])->name('pertemuan.store');
    Route::get('/pertemuan/{pertemuan}/qr', [DosenPertemuanController::class, 'showQr'])->name('pertemuan.qr');
    Route::post('/pertemuan/{pertemuan}/regenerate-qr', [DosenPertemuanController::class, 'regenerateQr'])->name('pertemuan.regenerate_qr');
    Route::get('/pertemuan/{pertemuan}/live-attendance', [DosenPertemuanController::class, 'liveAttendance'])->name('pertemuan.live_attendance');
    Route::get('/jadwal/{jadwalKuliah}/export-rekap', [DosenPertemuanController::class, 'exportRekap'])->name('jadwal.export_rekap');
    Route::post('/jadwal/{jadwalKuliah}/update-lokasi', [DosenPertemuanController::class, 'updateLokasi'])->name('jadwal.update_lokasi');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/scan', [PresensiController::class, 'scanPage'])->name('scan');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('riwayat');
});
