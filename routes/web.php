<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardMahasiswaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LuckyStar Web Routes
|--------------------------------------------------------------------------
*/

// Landing page → redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard redirect berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->isAdmin() || $user->isDosen()) {
        return redirect('/admin');
    }

    return redirect()->route('mahasiswa.dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Routes Mahasiswa — Dashboard & SIAKAD Mini
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [DashboardMahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/katalog', [DashboardMahasiswaController::class, 'katalog'])->name('mahasiswa.katalog');
    Route::get('/dosen', [DashboardMahasiswaController::class, 'dosen'])->name('mahasiswa.dosen');
});

/*
|--------------------------------------------------------------------------
| Routes Mahasiswa — Presensi & Riwayat
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/permit', [AttendanceController::class, 'submitPermit'])->name('absensi.permit');
    Route::get('/riwayat', [AttendanceController::class, 'history'])->name('riwayat');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

