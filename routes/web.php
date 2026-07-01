<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Show login form
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login POST
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Temporary admin dashboard route
Route::get('/admin_dashboard', function () {
    return view('admin_dashboard');
})->name('admin.dashboard');

// Mainpage — running text display
use App\Http\Controllers\MainpageController;

Route::get('/mainpage', [MainpageController::class, 'index'])->name('mainpage');
