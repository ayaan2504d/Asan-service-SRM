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
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportBusinessPdf'])->name('dashboard.export-pdf');
    Route::get('/search', [DashboardController::class, 'globalSearch'])->name('global.search');
    Route::post('/reminders/{solarClient}/dismiss', [DashboardController::class, 'dismissReminder'])->name('reminders.dismiss');
    Route::post('/service-reminders/{reminder}/complete', [DashboardController::class, 'completeReminder'])->name('reminders.complete');

    // Solar Clients
    Route::resource('solar-clients', SolarClientController::class);

    // Marketing Leads specific routes
    Route::get('/leads/check-phone', [LeadController::class, 'checkPhone'])->name('leads.check-phone');
    Route::post('/leads/{lead}/convert/solar', [LeadController::class, 'convertToSolar'])->name('leads.convert.solar');
    Route::post('/leads/{lead}/convert/sofa', [LeadController::class, 'convertToSofa'])->name('leads.convert.sofa');
    Route::post('/leads/{lead}/convert/deep', [LeadController::class, 'convertToDeep'])->name('leads.convert.deep');
    
    // Marketing Leads Resource
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
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');

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
