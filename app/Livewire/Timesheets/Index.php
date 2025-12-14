<?php

namespace App\Livewire\Timesheets;

use App\Models\Client;
use App\Models\TimeRegistration;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $selectedClientId = '';
    public $period = 'month';
    public $date;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function render()
    {
        $clients = Client::orderBy('name')->get();
        $registrations = collect();
        $totalHours = 0;
        $totalRevenue = 0;
        $totalDistance = 0;
        $periodLabel = '';

        if ($this->selectedClientId) {
            $selectedDate = Carbon::parse($this->date);
            $query = TimeRegistration::where('client_id', $this->selectedClientId)
                ->with(['project', 'project.currency', 'client']);

            switch ($this->period) {
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
            $totalDistance = $registrations->sum(fn($r) => $r->distance ?? 0);
            $totalRevenue = $registrations->sum(function ($reg) {
                if (!$reg->project) {
                    return 0;
                }
                return $reg->duration * $reg->project->hourly_rate;
            });
        }

        return view('livewire.timesheets.index', compact(
            'clients',
            'registrations',
            'totalHours',
            'totalRevenue',
            'totalDistance',
            'periodLabel'
        ));
    }
}
