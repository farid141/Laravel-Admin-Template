<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiskController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SubmenuController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'store']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::resource('/menu', MenuController::class);
Route::resource('/submenu', SubmenuController::class)->except(['show', 'create']);
Route::resource('/books', BookController::class);
Route::resource('/disks', DiskController::class);
Route::resource('/members', MemberController::class);
