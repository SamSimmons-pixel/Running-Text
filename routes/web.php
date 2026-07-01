<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Show login form
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login POST
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/mainpage', function () {
    return view('mainpage');
});
