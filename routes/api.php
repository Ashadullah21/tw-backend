<?php

use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are automatically prefixed with /api by Laravel's
| RouteServiceProvider and are stateless (no session, no CSRF).
|
*/

// Health-check endpoint — useful for Render's uptime monitoring
Route::get('/health', fn() => response()->json(['status' => 'ok', 'service' => 'tw-downloader']));

// Core extraction endpoint
Route::post('/extract', [VideoController::class, 'extract']);

// Streaming proxy download endpoint (forces direct browser download popup)
Route::get('/download', [VideoController::class, 'download']);

// Contact message submissions from frontend
Route::post('/contact', [ContactController::class, 'store'])->name('api.contact.store');
