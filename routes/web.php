<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Halaman login (form simulasi)
Route::get('/', [PageController::class, 'login'])->name('login');

// Proses login: validasi lalu redirect membawa username sebagai request parameter
Route::post('/login', [PageController::class, 'authenticate'])->name('login.process');

// Halaman yang menerima ?username=... dari request
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');

// Pengelolaan produk (CRUD)
Route::get('/pengelolaan', [PageController::class, 'pengelolaan'])->name('pengelolaan');
Route::post('/pengelolaan', [PageController::class, 'store'])->name('menu.store');
Route::put('/pengelolaan/{kode}', [PageController::class, 'update'])->name('menu.update');
Route::delete('/pengelolaan/{kode}', [PageController::class, 'destroy'])->name('menu.destroy');
