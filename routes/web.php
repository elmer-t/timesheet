<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\TimeRegistrationController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Application routes (requires authentication and tenant)
Route::prefix('app')->middleware(['auth', 'tenant'])->name('app.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Time Registrations (all users)
    Route::resource('registrations', TimeRegistrationController::class);
    
    // Timesheets (all users)
    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::get('/timesheets/print', [TimesheetController::class, 'print'])->name('timesheets.print');
    
    // Admin-only routes
    Route::middleware('tenant.admin')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('invitations', InvitationController::class)->except(['show', 'edit', 'update']);
        
        // Tenant Settings
        Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [TenantSettingsController::class, 'update'])->name('settings.update');
    });
});

