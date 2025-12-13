<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Get summary statistics
        $totalRegistrations = $user->timeRegistrations()->count();
        $totalHours = $user->timeRegistrations()->sum('duration');
        $totalRevenue = $user->timeRegistrations()
            ->with('project')
            ->get()
            ->sum(function ($registration) {
                return $registration->duration * $registration->project->hourly_rate;
            });
        
        // Recent registrations
        $recentRegistrations = $user->timeRegistrations()
            ->with(['client', 'project.currency'])
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        return view('app.dashboard', compact(
            'totalRegistrations',
            'totalHours',
            'totalRevenue',
            'recentRegistrations'
        ));
    }
}
