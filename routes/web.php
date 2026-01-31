<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

/*
    Public Routes
*/
Route::get('/', [LinkController::class, 'index']);
Route::post('/shorten', [LinkController::class, 'store']);

/*
    Authentication Routes
*/
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

/*
    User Routes
*/
Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
Route::get('/file/{code}', [LinkController::class, 'preview'])->name('file.preview');
Route::get('/file/{code}/download', [LinkController::class, 'download'])->name('file.download');

/*
    Redirect Short Code
*/
// Route::get('/{code}', [LinkController::class, 'redirect']);
Route::get('/{code}', [LinkController::class, 'redirect'])->where('code', '[A-Za-z0-9]{6}');
