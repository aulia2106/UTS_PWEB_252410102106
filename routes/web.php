<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'login'])->name('login');

Route::post('/login', [PageController::class, 'authenticate'])->name('login.process');

Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');

Route::get('/pengelolaan', [PageController::class, 'pengelolaan'])->name('pengelolaan');
Route::post('/pengelolaan', [PageController::class, 'store'])->name('menu.store');
Route::put('/pengelolaan/{kode}', [PageController::class, 'update'])->name('menu.update');
Route::delete('/pengelolaan/{kode}', [PageController::class, 'destroy'])->name('menu.destroy');
