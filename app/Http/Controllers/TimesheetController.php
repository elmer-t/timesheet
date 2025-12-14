<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistration;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    public function index(Request $request): View
    {
        $clients = Client::orderBy('name')->get();
        $selectedClientId = $request->input('client_id');
        $period = $request->input('period', 'month');
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $selectedDate = Carbon::parse($date);
        $registrations = collect();
        $totalHours = 0;
        $totalRevenue = 0;
        $periodLabel = '';

        if ($selectedClientId) {
            $query = TimeRegistration::where('client_id', $selectedClientId)
                ->with(['project.currency', 'client']);

            switch ($period) {
                case 'day':
                    $query->whereDate('date', $selectedDate);
                    $periodLabel = $selectedDate->format('F j, Y');
                    break;
                
                case 'week':
                    $startOfWeek = $selectedDate->copy()->startOfWeek();
                    $endOfWeek = $selectedDate->copy()->endOfWeek();
                    $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
                    $periodLabel = 'Week of ' . $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y');
                    break;
                
                case 'month':
                default:
                    $query->whereYear('date', $selectedDate->year)
                          ->whereMonth('date', $selectedDate->month);
                    $periodLabel = $selectedDate->format('F Y');
                    break;
            }

            $registrations = $query->orderBy('date', 'desc')->get();
            $totalHours = $registrations->sum('duration');
            $totalRevenue = $registrations->sum(function ($reg) {
                if (!$reg->project) {
                    return 0;
                }
                return $reg->duration * $reg->project->hourly_rate;
            });
        }

        return view('app.timesheets.index', compact(
            'clients',
            'selectedClientId',
            'period',
            'date',
            'registrations',
            'totalHours',
            'totalRevenue',
            'periodLabel'
        ));
    }

    public function print(Request $request): View
    {
        $clientId = $request->input('client_id');
        $period = $request->input('period', 'month');
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $selectedDate = Carbon::parse($date);
        $client = Client::findOrFail($clientId);
        
        $query = TimeRegistration::where('client_id', $clientId)
            ->with(['project', 'project.currency', 'user']);

        switch ($period) {
            case 'day':
                $query->whereDate('date', $selectedDate);
                $periodLabel = $selectedDate->format('F j, Y');
                break;
            
            case 'week':
                $startOfWeek = $selectedDate->copy()->startOfWeek();
                $endOfWeek = $selectedDate->copy()->endOfWeek();
                $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
                $periodLabel = 'Week of ' . $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y');
                break;
            
            case 'month':
            default:
                $query->whereYear('date', $selectedDate->year)
                      ->whereMonth('date', $selectedDate->month);
                $periodLabel = $selectedDate->format('F Y');
                break;
        }

        $registrations = $query->orderBy('date')->get();
        
        // Update all registrations to invoiced status
        foreach ($registrations as $registration) {
            if ($registration->status === TimeRegistration::STATUS_READY_TO_INVOICE) {
                $registration->status = TimeRegistration::STATUS_INVOICED;
                $registration->save();
            }
        }
        
        $totalHours = $registrations->sum('duration');
        $totalDistance = $registrations->sum(fn($r) => $r->distance ?? 0);
        $totalRevenue = $registrations->sum(function ($reg) {
            if (!$reg->project) {
                return 0;
            }
            return $reg->duration * $reg->project->hourly_rate;
        });

        return view('app.timesheets.print', compact(
            'client',
            'registrations',
            'totalHours',
            'totalDistance',
            'totalRevenue',
            'periodLabel'
        ));
    }
}
