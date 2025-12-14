<?php

namespace App\Livewire\TimeRegistrations;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeRegistration;
use Livewire\Component;

class CreateEdit extends Component
{
    public $registrationId = null;
    public $client_id = '';
    public $project_id = '';
    public $date;
    public $duration = '';
    public $description = '';
    public $status = 'ready_to_invoice';
    public $location = '';
    public $distance = '';
    
    public $clients = [];
    public $projects = [];
    public $filteredProjects = [];

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(TimeRegistration::getStatuses())),
            'location' => 'nullable|string|max:255',
            'distance' => 'nullable|integer|min:0|max:999999',
        ];
    }

    public function mount($id = null)
    {
        $this->date = date('Y-m-d');
        $this->clients = Client::orderBy('name')->get();
        $this->projects = Project::availableForRegistration()
            ->with('client')
            ->orderBy('name')
            ->get();
        $this->filteredProjects = $this->projects;

        if ($id) {
            $registration = TimeRegistration::findOrFail($id);
            $this->authorize('update', $registration);
            
            $this->registrationId = $registration->id;
            $this->client_id = $registration->client_id;
            $this->project_id = $registration->project_id;
            $this->date = $registration->date->format('Y-m-d');
            $this->duration = $registration->duration;
            $this->description = $registration->description;
            $this->status = $registration->status;
            $this->location = $registration->location;
            $this->distance = $registration->distance;
            
            $this->filterProjects();
        } else {
            // Get last used client and project from session
            $this->client_id = session('last_client_id', '');
            $this->project_id = session('last_project_id', '');
            
            if ($this->client_id) {
                $this->filterProjects();
            }
        }
    }

    public function updatedClientId()
    {
        $this->project_id = '';
        $this->filterProjects();
    }

    public function filterProjects()
    {
        if ($this->client_id) {
            $this->filteredProjects = $this->projects->where('client_id', $this->client_id);
        } else {
            $this->filteredProjects = $this->projects;
        }
    }

    public function save()
    {
        $validated = $this->validate();

        // Verify project can accept time registration if project is provided
        if (!empty($validated['project_id'])) {
            $project = Project::findOrFail($validated['project_id']);
            if (!$project->canRegisterTime()) {
                $this->addError('project_id', 'This project is not available for time registration.');
                return;
            }
        }

        if ($this->registrationId) {
            $registration = TimeRegistration::findOrFail($this->registrationId);
            $this->authorize('update', $registration);
            $registration->update($validated);
            $message = 'Time registration updated successfully.';
        } else {
            $validated['user_id'] = auth()->id();
            TimeRegistration::create($validated);
            $message = 'Time registration created successfully.';
            
            // Remember last used client and project
            session([
                'last_client_id' => $validated['client_id'],
                'last_project_id' => $validated['project_id'] ?? null,
            ]);
        }

        session()->flash('success', $message);
        return redirect()->route('app.registrations.index');
    }

    public function render()
    {
        return view('livewire.time-registrations.create-edit', [
            'statuses' => TimeRegistration::getStatuses(),
        ]);
    }
}
