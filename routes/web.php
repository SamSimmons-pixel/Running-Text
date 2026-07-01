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

// ── Admin dashboard (admin only — guarded inside controller) ─────
Route::get ('/admin_dashboard',                  [admin_dashboard_Controller::class, 'index'])->name('admin.dashboard');
Route::post('/admin_dashboard',                  [admin_dashboard_Controller::class, 'store'])->name('admin.kajian.store');
Route::post('/admin_dashboard/{id}',             [admin_dashboard_Controller::class, 'update'])->name('admin.kajian.update');
Route::post('/admin_dashboard/{id}/toggle',      [admin_dashboard_Controller::class, 'toggle'])->name('admin.kajian.toggle');
Route::post('/admin_dashboard/{id}/toggleLogo',  [admin_dashboard_Controller::class, 'toggleLogo'])->name('admin.kajian.toggleLogo');
Route::post('/admin_dashboard/{id}/delete',      [admin_dashboard_Controller::class, 'destroy'])->name('admin.kajian.destroy');
