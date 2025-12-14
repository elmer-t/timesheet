<?php

namespace App\Livewire\Projects;

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
    public $start_date;
    public $end_date = '';
    public $hourly_rate = '';
    
    public $clients = [];
    public $currencies = [];

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'required|exists:currencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hourly_rate' => 'required|numeric|min:0',
        ];
    }

    public function mount($id = null)
    {
        $this->clients = Client::orderBy('name')->get();
        $this->currencies = Currency::orderBy('code')->get();
        $this->currency_id = auth()->user()->tenant->default_currency_id;
        $this->start_date = date('Y-m-d');

        if ($id) {
            $project = Project::findOrFail($id);
            $this->projectId = $project->id;
            $this->client_id = $project->client_id;
            $this->currency_id = $project->currency_id;
            $this->name = $project->name;
            $this->description = $project->description;
            $this->status = $project->status;
            $this->start_date = $project->start_date->format('Y-m-d');
            $this->end_date = $project->end_date?->format('Y-m-d') ?? '';
            $this->hourly_rate = $project->hourly_rate;
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
        return view('livewire.projects.create-edit');
    }
}
