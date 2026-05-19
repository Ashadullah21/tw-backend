<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

// Redirect root to admin login or welcome page (optional, but keep default welcome behavior intact)
Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes (using our custom AdminAuth middleware)
Route::middleware([AdminAuth::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts');
    Route::post('/admin/contacts/{id}/read', [ContactController::class, 'markRead'])->name('admin.contacts.read');
});
