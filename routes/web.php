<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SolarClientController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SofaCleaningController;
use App\Http\Controllers\DeepCleaningController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [DashboardController::class, 'globalSearch'])->name('global.search');
    Route::post('/reminders/{solarClient}/dismiss', [DashboardController::class, 'dismissReminder'])->name('reminders.dismiss');

    // Solar Clients
    Route::resource('solar-clients', SolarClientController::class);

    // Marketing Leads
    Route::resource('leads', LeadController::class);

    // Sofa Cleaning
    Route::resource('sofa-cleaning', SofaCleaningController::class);

    // Deep Cleaning
    Route::resource('deep-cleaning', DeepCleaningController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['show']);

    // Customer History
    Route::get('/customer-history', [CustomerHistoryController::class, 'index'])->name('customer-history.index');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Settings (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/users', [SettingsController::class, 'storeUser'])->name('settings.users.store');
        Route::post('/settings/users/{user}/toggle', [SettingsController::class, 'toggleUser'])->name('settings.users.toggle');
        Route::delete('/settings/users/{user}', [SettingsController::class, 'destroyUser'])->name('settings.users.destroy');
        Route::post('/settings/whatsapp', [SettingsController::class, 'updateWhatsApp'])->name('settings.whatsapp');
    });

    // Password update (any authenticated user)
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
});
