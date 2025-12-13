<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
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
        
        // Calendar data
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Get registrations for the selected month
        $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        
        $monthRegistrations = $user->timeRegistrations()
            ->with(['project', 'client'])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->orderBy('date', 'asc')
            ->get();

        return view('app.dashboard', compact(
            'totalRegistrations',
            'totalHours',
            'totalRevenue',
            'month',
            'year',
            'monthRegistrations'
        ));
    }
}
