<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PageController;
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
Route::get('/analytics/data', [LinkController::class, 'analyticsData'])
    ->name('analytics.data');

/*
    Redirect Short Code
*/
// Route::get('/{code}', [LinkController::class, 'redirect']);
Route::get('/{code}', [LinkController::class, 'redirect'])->where('code', '[A-Za-z0-9]{6}');

/*
    Static Pages
*/
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/support', [PageController::class, 'support'])->name('support');
Route::get('/error404', [PageController::class, 'error404'])->name('error404');

/*
    Admin Routes
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');

    Route::get('/links', [AdminController::class, 'links'])->name('admin.links');

    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');

    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');

    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
