<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubmenuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::resource('/menu', MenuController::class)->except(['show', 'create']);
    Route::resource('/submenu', SubmenuController::class)->except(['show', 'create']);
    Route::resource('/user', UserController::class)->except(['show', 'create']);
    Route::resource('/role', RoleController::class)->except(['show', 'create']);
    Route::resource('/permission', PermissionController::class)->except(['show', 'create']);
});
