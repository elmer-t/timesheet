<?php

namespace App\Livewire;

use App\Models\TimeRegistration;
use App\Models\Client;
use App\Models\Project;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $month;
    public $year;
    public $showModal = false;
    public $selectedDate;
    public $editingRegistrations = [];
    
    // Form fields
    public $registration_id;
    public $client_id;
    public $project_id;
    public $date;
    public $duration;
    public $description;
    public $status = 'ready_to_invoice';
    public $location;
    public $distance;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function goToToday()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function updatedMonth()
    {
        if ($this->month < 1) $this->month = 1;
        if ($this->month > 12) $this->month = 12;
    }

    public function updatedYear()
    {
        if ($this->year < 2000) $this->year = 2000;
        if ($this->year > 2100) $this->year = 2100;
    }

    public function openDay($dateString)
    {
        $this->selectedDate = $dateString;
        $this->date = $dateString;
        
        // Get existing registrations for this date
        $this->editingRegistrations = auth()->user()
            ->timeRegistrations()
            ->with(['client', 'project'])
            ->whereDate('date', $dateString)
            ->get()
            ->toArray();
        
        // Reset form
        $this->reset(['registration_id', 'client_id', 'project_id', 'duration', 'description']);
        
        $this->showModal = true;
    }

    public function editRegistration($id)
    {
        $registration = TimeRegistration::findOrFail($id);
        
        $this->registration_id = $registration->id;
        $this->client_id = $registration->client_id;
        $this->project_id = $registration->project_id;
        $this->date = $registration->date->format('Y-m-d');
        $this->duration = $registration->duration;
        $this->description = $registration->description;
        $this->status = $registration->status;
        $this->location = $registration->location;
        $this->distance = $registration->distance;
    }

    public function deleteRegistration($id)
    {
        $registration = TimeRegistration::findOrFail($id);
        $this->authorize('delete', $registration);
        $registration->delete();
        
        // Refresh the editing list
        $this->editingRegistrations = auth()->user()
            ->timeRegistrations()
            ->with(['client', 'project'])
            ->whereDate('date', $this->selectedDate)
            ->get()
            ->toArray();
    }

    public function save()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.1|max:24',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:ready_to_invoice,invoiced,paid',
            'location' => 'nullable|string|max:255',
            'distance' => 'nullable|numeric|min:0|max:9999.99',
        ]);

        if ($this->registration_id) {
            $registration = TimeRegistration::findOrFail($this->registration_id);
            $registration->update([
                'client_id' => $this->client_id,
                'project_id' => $this->project_id,
                'date' => $this->date,
                'duration' => $this->duration,
                'description' => $this->description,
                'status' => $this->status,
                'location' => $this->location,
                'distance' => $this->distance,
            ]);
        } else {
            TimeRegistration::create([
                'user_id' => auth()->id(),
                'client_id' => $this->client_id,
                'project_id' => $this->project_id,
                'date' => $this->date,
                'duration' => $this->duration,
                'description' => $this->description,
                'status' => $this->status,
                'location' => $this->location,
                'distance' => $this->distance,
            ]);
        }

        // Refresh the editing list
        $this->editingRegistrations = auth()->user()
            ->timeRegistrations()
            ->with(['client', 'project'])
            ->whereDate('date', $this->selectedDate)
            ->get()
            ->toArray();

        // Reset form
        $this->reset(['registration_id', 'client_id', 'project_id', 'duration', 'description']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['registration_id', 'client_id', 'project_id', 'duration', 'description', 'status', 'location', 'distance', 'selectedDate', 'editingRegistrations']);
        $this->status = 'ready_to_invoice';
    }

    public function render()
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
        $monthStart = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        
        // Get all registrations for the month grouped by date
        $monthRegistrations = $user->timeRegistrations()
            ->with(['project', 'client'])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get()
            ->groupBy(function($registration) {
                return $registration->date->format('Y-m-d');
            })
            ->map(function($dayRegistrations) {
                $byStatus = [
                    'ready_to_invoice' => 0,
                    'invoiced' => 0,
                    'paid' => 0,
                ];
                
                foreach ($dayRegistrations as $reg) {
                    if (isset($byStatus[$reg->status])) {
                        $byStatus[$reg->status] += $reg->duration;
                    }
                }
                
                return [
                    'total_hours' => $dayRegistrations->sum('duration'),
                    'total_distance' => $dayRegistrations->sum('distance'),
                    'count' => $dayRegistrations->count(),
                    'by_status' => $byStatus,
                ];
            });

        // Build calendar grid
        $calendarStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
        
        $calendarDays = [];
        $currentDate = $calendarStart->copy();
        
        while ($currentDate <= $calendarEnd) {
            $dateString = $currentDate->format('Y-m-d');
            $isCurrentMonth = $currentDate->month == $this->month;
            
            $dayData = [
                'date' => $currentDate->copy(),
                'dateString' => $dateString,
                'day' => $currentDate->day,
                'isCurrentMonth' => $isCurrentMonth,
                'isToday' => $currentDate->isToday(),
                'hours' => 0,
                'distance' => 0,
                'count' => 0,
                'by_status' => [
                    'ready_to_invoice' => 0,
                    'invoiced' => 0,
                    'paid' => 0,
                ],
            ];
            
            if (isset($monthRegistrations[$dateString])) {
                $dayData['hours'] = $monthRegistrations[$dateString]['total_hours'];
                $dayData['distance'] = $monthRegistrations[$dateString]['total_distance'];
                $dayData['count'] = $monthRegistrations[$dateString]['count'];
                $dayData['by_status'] = $monthRegistrations[$dateString]['by_status'];
            }
            
            $calendarDays[] = $dayData;
            $currentDate->addDay();
        }
        
        // Split into weeks
        $weeks = array_chunk($calendarDays, 7);
        
        // Get clients and projects for the form
        $clients = Client::where('tenant_id', $user->tenant_id)->orderBy('name')->get();
        $projects = $this->client_id 
            ? Project::where('client_id', $this->client_id)->orderBy('name')->get()
            : collect();

        return view('livewire.dashboard', compact(
            'totalRegistrations',
            'totalHours',
            'totalRevenue',
            'readyToInvoiceStats',
            'invoicedStats',
            'paidStats',
            'weeks',
            'clients',
            'projects'
        ));
    }
}
