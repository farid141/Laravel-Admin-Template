<?php

use App\Http\Controllers\DashboradController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'store']);

Route::get('/dashboard', [DashboradController::class, 'index']);
