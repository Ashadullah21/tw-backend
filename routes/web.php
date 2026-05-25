<?php
 
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\Mp3DownloadController;
use App\Http\Controllers\Admin\FailedDownloadController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

// Redirect root to admin login or welcome page (optional, but keep default welcome behavior intact)
Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes (using our custom AdminAuth middleware)
Route::middleware([AdminAuth::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts');
    Route::post('/admin/contacts/{id}/read', [ContactController::class, 'markRead'])->name('admin.contacts.read');

    // MP3 Downloads Log Route
    Route::get('/admin/mp3-downloads', [Mp3DownloadController::class, 'index'])->name('admin.mp3_downloads');

    // Failed Downloads Log Route
    Route::get('/admin/failed-downloads', [FailedDownloadController::class, 'index'])->name('admin.failed_downloads');

    // FAQs Admin Panel CRUD Routes
    Route::get('/admin/faqs', [FaqController::class, 'index'])->name('admin.faqs.index');
    Route::get('/admin/faqs/create', [FaqController::class, 'create'])->name('admin.faqs.create');
    Route::post('/admin/faqs', [FaqController::class, 'store'])->name('admin.faqs.store');
    Route::get('/admin/faqs/{id}/edit', [FaqController::class, 'edit'])->name('admin.faqs.edit');
    Route::post('/admin/faqs/{id}', [FaqController::class, 'update'])->name('admin.faqs.update');
    Route::post('/admin/faqs/{id}/delete', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');

    // Export CSV Routes
    Route::get('/admin/export/download-logs', [DashboardController::class, 'export'])->name('admin.export.download_logs');
    Route::get('/admin/export/user-activities', [DashboardController::class, 'exportUserActivities'])->name('admin.export.user_activities');
    Route::get('/admin/export/contacts', [ContactController::class, 'export'])->name('admin.export.contacts');
    Route::get('/admin/export/mp3-downloads', [Mp3DownloadController::class, 'export'])->name('admin.export.mp3_downloads');
    Route::get('/admin/export/failed-downloads', [FailedDownloadController::class, 'export'])->name('admin.export.failed_downloads');
    Route::get('/admin/export/faqs', [FaqController::class, 'export'])->name('admin.export.faqs');
});
