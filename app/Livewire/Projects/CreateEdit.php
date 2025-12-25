<?php

namespace App\Livewire\Projects;

use App\Http\Requests\ProjectRequest;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Project;
use Livewire\Component;

class CreateEdit extends Component
{
    public $projectId = null;
    public $client_id = '';
    public $currency_id = '';
    public $name = '';
    public $description = '';
    public $status = 'active';
    public $is_paid = true;
    public $start_date;
    public $end_date = '';
    public $hourly_rate = '';
    public $mileage_allowance = '';
    
    public $clients = [];
    public $currencies = [];

    protected function rules()
    {
        return (new ProjectRequest())->rules();
    }

    public function mount($id = null, $client_id = null)
    {
        $this->clients = Client::orderBy('name')->get();
        $this->currencies = Currency::orderBy('code')->get();
        $this->currency_id = auth()->user()->tenant->default_currency_id;
        $this->start_date = date('Y-m-d');

        // Pre-select client if passed from client detail page
        if ($client_id) {
            $this->client_id = $client_id;
        }

        if ($id) {
            $project = Project::findOrFail($id);
            $this->projectId = $project->id;
            $this->client_id = $project->client_id;
            $this->currency_id = $project->currency_id;
            $this->name = $project->name;
            $this->description = $project->description;
            $this->status = $project->status;
            $this->is_paid = $project->is_paid;
            $this->start_date = $project->start_date->format('Y-m-d');
            $this->end_date = $project->end_date?->format('Y-m-d') ?? '';
            $this->hourly_rate = $project->hourly_rate;
            $this->mileage_allowance = $project->mileage_allowance;
        }
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['tenant_id'] = auth()->user()->tenant_id;

        try {
            if ($this->projectId) {
                $project = Project::findOrFail($this->projectId);
                $project->update($validated);
                $message = 'Project updated successfully.';
            } else {
                $tenant = auth()->user()->tenant;
                $validated['project_number'] = $tenant->generateProjectNumber();
                Project::create($validated);
                $message = 'Project created successfully.';
            }

            session()->flash('success', $message);
            return redirect()->route('app.projects.index');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a unique constraint violation
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'UNIQUE constraint')) {
                $this->addError('name', 'A project with this number already exists. Please try again.');
                return;
            }
            throw $e;
        }
    }

    public function render()
    {
        $stats = null;
        $project = null;
        
        if ($this->projectId) {
            $project = Project::with('timeRegistrations')->findOrFail($this->projectId);
            $registrations = $project->timeRegistrations;
            
            $totalDistance = $registrations->sum('distance');
            $mileageReimbursement = $project->mileage_allowance ? ($totalDistance * $project->mileage_allowance) : 0;
            
            $stats = [
                'total_registrations' => $registrations->count(),
                'total_hours' => $registrations->sum('duration'),
                'total_distance' => $totalDistance,
                'mileage_reimbursement' => $mileageReimbursement,
                'total_revenue' => $registrations->sum(function($r) {
                    return $r->duration * $r->project->hourly_rate;
                }),
                'by_status' => [
                    'ready_to_invoice' => $registrations->where('status', 'ready_to_invoice')->count(),
                    'invoiced' => $registrations->where('status', 'invoiced')->count(),
                    'paid' => $registrations->where('status', 'paid')->count(),
                ],
            ];
        }
        
        return view('livewire.projects.create-edit', compact('stats', 'project'));
    }
}
