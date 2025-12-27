<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\TimeRegistrationController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\UserController;
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
    Route::get('/calendar', [DashboardController::class, 'index'])->name('calendar');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Time Registrations (all users)
    Route::resource('registrations', TimeRegistrationController::class);
    
    // API endpoint for fetching projects by client
    Route::get('/clients/{client}/projects', [ClientController::class, 'getProjects'])->name('clients.projects');
    
    // Timesheets (all users)
    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::get('/timesheets/print', [TimesheetController::class, 'print'])->name('timesheets.print');
    
    // Admin-only routes
    Route::middleware('tenant.admin')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('users', UserController::class);
        
        // Send onboarding email
        Route::post('/users/{user}/send-onboarding', [UserController::class, 'sendOnboarding'])
            ->name('users.send-onboarding');
        
        // Trashed Records
        Route::get('/clients/trashed/list', [ClientController::class, 'trashed'])->name('clients.trashed');
        Route::post('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
        Route::delete('/clients/{id}/force', [ClientController::class, 'forceDestroy'])->name('clients.force-destroy');
        
        Route::get('/projects/trashed/list', [ProjectController::class, 'trashed'])->name('projects.trashed');
        Route::post('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
        Route::delete('/projects/{id}/force', [ProjectController::class, 'forceDestroy'])->name('projects.force-destroy');
        
        // Tenant Settings
        Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [TenantSettingsController::class, 'update'])->name('settings.update');
    });
});

// Super Admin routes (requires super admin role)
Route::prefix('super')->middleware(['auth', 'super.admin'])->name('super.')->group(function () {
    // Tenant Management
    Route::get('/tenants', \App\Livewire\Tenants\Index::class)->name('tenants.index');
    
    // Job Monitor
    Route::get('/jobs', \App\Livewire\JobMonitor::class)->name('jobs.monitor');
});

