<?php

namespace App\Livewire;

use App\Models\TimeRegistration;
use Livewire\Component;
use Carbon\Carbon;

class Analytics extends Component
{
    public $startDate;
    public $endDate;
    public $clientFilter;
    public $projectFilter;
    public $period = 'all_time'; // all_time, this_month, last_month, this_year, custom

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedPeriod()
    {
        switch ($this->period) {
            case 'this_month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
            case 'all_time':
                $this->startDate = null;
                $this->endDate = null;
                break;
        }
    }

    public function render()
    {
        $user = auth()->user();
        
        if (!$user) {
            return view('livewire.analytics', [
                'totalRegistrations' => 0,
                'totalHours' => 0,
                'totalRevenue' => 0,
                'totalDistance' => 0,
                'readyToInvoiceStats' => ['count' => 0, 'hours' => 0, 'revenue' => 0],
                'invoicedStats' => ['count' => 0, 'hours' => 0, 'revenue' => 0],
                'paidStats' => ['count' => 0, 'hours' => 0, 'revenue' => 0],
                'clients' => collect(),
                'projects' => collect(),
            ]);
        }

        // Build query based on filters
        $query = $user->timeRegistrations()->with(['project', 'client']);

        // Apply period filter
        if ($this->period !== 'all_time' && $this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        // Apply client filter
        if ($this->clientFilter) {
            $query->where('client_id', $this->clientFilter);
        }

        // Apply project filter
        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        // Get all registrations
        $registrations = $query->get();

        // Overall metrics
        $totalRegistrations = $registrations->count();
        $totalHours = $registrations->sum('duration');
        $totalDistance = $registrations->sum(fn($r) => $r->distance ?? 0);
        $totalRevenue = $registrations->sum(function($registration) {
            if (!$registration->project) {
                return 0;
            }
            return $registration->duration * $registration->project->hourly_rate;
        });

        // Status-based metrics
        $readyToInvoiceStats = [
            'count' => $registrations->where('status', 'ready_to_invoice')->count(),
            'hours' => $registrations->where('status', 'ready_to_invoice')->sum('duration'),
            'revenue' => $registrations->where('status', 'ready_to_invoice')->sum(fn($r) => $r->revenue),
        ];

        $invoicedStats = [
            'count' => $registrations->where('status', 'invoiced')->count(),
            'hours' => $registrations->where('status', 'invoiced')->sum('duration'),
            'revenue' => $registrations->where('status', 'invoiced')->sum(fn($r) => $r->revenue),
        ];

        $paidStats = [
            'count' => $registrations->where('status', 'paid')->count(),
            'hours' => $registrations->where('status', 'paid')->sum('duration'),
            'revenue' => $registrations->where('status', 'paid')->sum(fn($r) => $r->revenue),
        ];

        // Get clients and projects for filters
        $clients = \App\Models\Client::where('tenant_id', $user->tenant_id)
            ->orderBy('name')
            ->get();

        $projects = $this->clientFilter
            ? \App\Models\Project::where('client_id', $this->clientFilter)->orderBy('name')->get()
            : \App\Models\Project::whereHas('client', function($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            })->orderBy('name')->get();

        return view('livewire.analytics', compact(
            'totalRegistrations',
            'totalHours',
            'totalRevenue',
            'totalDistance',
            'readyToInvoiceStats',
            'invoicedStats',
            'paidStats',
            'clients',
            'projects'
        ));
    }
}
