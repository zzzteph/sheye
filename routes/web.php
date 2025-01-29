<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;



Route::middleware(['auth'])->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('index');;
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.main');
});
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'do_login'])->name('auth.do_login');;