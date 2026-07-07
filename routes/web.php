<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin_dashboard_Controller;
use App\Http\Controllers\MainpageController;

// ── Auth ──────────────────────────────────────────────────────────
Route::get('/',       [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

// ── Mainpage (regular user) ───────────────────────────────────────
Route::get('/mainpage', [MainpageController::class, 'index'])->name('mainpage');

use App\Http\Controllers\NarasumberController;
use App\Http\Controllers\TempatController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\InformasiUmumController;
use App\Http\Controllers\AcaraController;

// ── Admin dashboard (admin only — guarded inside controller) ─────
Route::get ('/admin_dashboard',                  [admin_dashboard_Controller::class, 'index'])->name('admin.dashboard');
Route::get ('/admin_dashboard/informasi',        [InformasiUmumController::class, 'index'])->name('admin.informasi');
Route::post('/admin_dashboard/informasi',        [InformasiUmumController::class, 'store'])->name('admin.informasi.store');
Route::post('/admin_dashboard/informasi/{id}',   [InformasiUmumController::class, 'update'])->name('admin.informasi.update');
Route::post('/admin_dashboard/informasi/{id}/toggle', [InformasiUmumController::class, 'toggle'])->name('admin.informasi.toggle');
Route::post('/admin_dashboard/informasi/{id}/delete', [InformasiUmumController::class, 'destroy'])->name('admin.informasi.destroy');

Route::get ('/admin_dashboard/kajian',            [admin_dashboard_Controller::class, 'kajian'])->name('admin.kajian');
Route::post('/admin_dashboard',                  [admin_dashboard_Controller::class, 'store'])->name('admin.kajian.store');
Route::get('/admin_dashboard/logo',               [admin_dashboard_Controller::class, 'logo'])->name('admin.logo');
// ── Acara Management ──────────────────────────────────────────
Route::get ('/admin_dashboard/acara',              [AcaraController::class, 'index'])->name('admin.acara');
Route::post('/admin_dashboard/acara',              [AcaraController::class, 'store'])->name('admin.acara.store');
Route::post('/admin_dashboard/acara/{id}',         [AcaraController::class, 'update'])->name('admin.acara.update');
Route::post('/admin_dashboard/acara/{id}/delete',  [AcaraController::class, 'destroy'])->name('admin.acara.destroy');
Route::post('/admin_dashboard/global-logo',      [admin_dashboard_Controller::class, 'uploadGlobalLogo'])->name('admin.logo.update');

// ── Narasumber Management ──────────────────────────────────────
Route::get ('/admin_dashboard/narasumber',       [NarasumberController::class, 'index'])->name('admin.narasumber');
Route::post('/admin_dashboard/narasumber',       [NarasumberController::class, 'store'])->name('admin.narasumber.store');
Route::post('/admin_dashboard/narasumber/{id}/delete', [NarasumberController::class, 'destroy'])->name('admin.narasumber.destroy');

// ── Tempat Management ──────────────────────────────────────────
Route::get ('/admin_dashboard/tempat',           [TempatController::class, 'index'])->name('admin.tempat');
Route::post('/admin_dashboard/tempat',           [TempatController::class, 'store'])->name('admin.tempat.store');
Route::post('/admin_dashboard/tempat/{id}/delete', [TempatController::class, 'destroy'])->name('admin.tempat.destroy');

// ── Kontak Management ──────────────────────────────────────────
Route::get ('/admin_dashboard/kontak',           [KontakController::class, 'index'])->name('admin.kontak');
Route::post('/admin_dashboard/kontak',           [KontakController::class, 'store'])->name('admin.kontak.store');
Route::post('/admin_dashboard/kontak/{id}/delete', [KontakController::class, 'destroy'])->name('admin.kontak.destroy');

// ── Pewaktuan Hijriah ──────────────────────────────────────────
use App\Http\Controllers\HijriTickerController;
use App\Http\Controllers\VmixDataController;

Route::get ('/admin_dashboard/pewaktuan-hijriah', [HijriTickerController::class, 'index'])->name('admin.hijri');
Route::post('/admin_dashboard/pewaktuan-hijriah', [HijriTickerController::class, 'update'])->name('admin.hijri.update');

// ── vMix Data API ──────────────────────────────────────────────
Route::get ('/api/vmix/hijri-ticker',             [VmixDataController::class, 'getTickerData'])->name('api.vmix.hijri-ticker');

// ── Kajian Wildcards (Define after static routes to prevent conflicts) ─
Route::post('/admin_dashboard/{id}',             [admin_dashboard_Controller::class, 'update'])->name('admin.kajian.update');
Route::post('/admin_dashboard/{id}/toggle',      [admin_dashboard_Controller::class, 'toggle'])->name('admin.kajian.toggle');
Route::post('/admin_dashboard/{id}/delete',      [admin_dashboard_Controller::class, 'destroy'])->name('admin.kajian.destroy');
