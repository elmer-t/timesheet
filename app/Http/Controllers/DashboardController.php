<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistration;
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
                if (!$registration->project) {
                    return 0;
                }
                return $registration->duration * $registration->project->hourly_rate;
            });
        
        // Status-based metrics
        $readyToInvoiceStats = [
            'count' => $user->timeRegistrations()->readyToInvoice()->count(),
            'hours' => $user->timeRegistrations()->readyToInvoice()->sum('duration'),
            'revenue' => $user->timeRegistrations()->readyToInvoice()->with('project')->get()->sum(fn($r) => $r->revenue),
        ];
        
        $invoicedStats = [
            'count' => $user->timeRegistrations()->invoiced()->count(),
            'hours' => $user->timeRegistrations()->invoiced()->sum('duration'),
            'revenue' => $user->timeRegistrations()->invoiced()->with('project')->get()->sum(fn($r) => $r->revenue),
        ];
        
        $paidStats = [
            'count' => $user->timeRegistrations()->paid()->count(),
            'hours' => $user->timeRegistrations()->paid()->sum('duration'),
            'revenue' => $user->timeRegistrations()->paid()->with('project')->get()->sum(fn($r) => $r->revenue),
        ];
        
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
            'readyToInvoiceStats',
            'invoicedStats',
            'paidStats',
            'month',
            'year',
            'monthRegistrations'
        ));
    }
}
