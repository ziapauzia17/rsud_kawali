<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SkpController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SkpAtasanController;
use App\Http\Controllers\RencanaKerjaController;
use App\Http\Controllers\PerilakuKerjaController;
use App\Http\Controllers\IndikatorKinerjaController;
use App\Http\Controllers\RencanaKerjaPegawaiController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('backend.dash.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    // bagian pangkat
    Route::resource('pangkat', PangkatController::class);
    Route::delete('/pangkat/destroy/{uuid}', [PangkatController::class, 'destroy'])->name('pangkat.destroy');

    // bagian atasan 
    Route::resource('atasans', AtasanController::class);
    Route::delete('/atasans/destroy/{uuid}', [AtasanController::class, 'destroy'])->name('atasans.destroy');

    // bagian skp
    Route::resource('skp', SkpController::class);
    Route::delete('/skp/destroy/{uuid}', [SkpController::class, 'destroy'])->name('skp.destroy');

    // bagian skp atasan
    Route::resource('skp_atasan', SkpAtasanController::class);
    Route::delete('/skp_atasan/destroy/{uuid}', [SkpAtasanController::class, 'destroy'])->name('skp_atasan.destroy');

    // bagian Rencana Kerja
    Route::resource('rencana-kerja', RencanaKerjaController::class);
    Route::resource('rencana-pegawai', RencanaKerjaPegawaiController::class);

    // bagian indikator
    Route::resource('indikator-kinerja', IndikatorKinerjaController::class);
    Route::delete('/indikator-kinerja/destroy/{uuid}', [IndikatorKinerjaController::class, 'destroy'])->name('indikator-kinerja.destroy');
    Route::put('/indikator-kinerja/{uuid}', [IndikatorKinerjaController::class, 'update'])->name('indikator-kinerja.update');

    // route perilaku
    Route::resource('perilaku', PerilakuKerjaController::class);
    Route::put('/perilaku/update/{uuid}', [PerilakuKerjaController::class, 'update'])->name('perilaku.update');
    Route::delete('/perilaku/destroy/{uuid}', [PerilakuKerjaController::class, 'destroy'])->name('perilaku.destroy');


    // bagian profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // bagian setting
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SettingController::class, 'destroy'])->name('settings.destroy');
});

require __DIR__ . '/auth.php';