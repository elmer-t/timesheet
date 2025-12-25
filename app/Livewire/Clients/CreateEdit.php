<?php

namespace App\Livewire\Clients;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Livewire\Component;

class CreateEdit extends Component
{
    public $clientId = null;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';

    protected function rules()
    {
        return (new ClientRequest())->rules();
    }

    public function mount($id = null)
    {
        if ($id) {
            $client = Client::findOrFail($id);
            $this->clientId = $client->id;
            $this->name = $client->name;
            $this->email = $client->email;
            $this->phone = $client->phone;
            $this->address = $client->address;
        }
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['tenant_id'] = auth()->user()->tenant_id;

        if ($this->clientId) {
            $client = Client::findOrFail($this->clientId);
            $client->update($validated);
            $message = 'Client updated successfully.';
        } else {
            Client::create($validated);
            $message = 'Client created successfully.';
        }

        session()->flash('success', $message);
        return redirect()->route('app.clients.index');
    }

    public function render()
    {
        $stats = null;
        
        if ($this->clientId) {
            $client = Client::with(['timeRegistrations', 'projects'])->findOrFail($this->clientId);
            $registrations = $client->timeRegistrations;
            
            $stats = [
                'total_projects' => $client->projects->count(),
                'active_projects' => $client->projects->where('status', 'active')->count(),
                'total_registrations' => $registrations->count(),
                'total_hours' => $registrations->sum('duration'),
                'total_distance' => $registrations->sum('distance'),
                'total_revenue' => $registrations->sum(function($r) {
                    return $r->duration * ($r->project->hourly_rate ?? 0);
                }),
                'by_status' => [
                    'ready_to_invoice' => $registrations->where('status', 'ready_to_invoice')->count(),
                    'invoiced' => $registrations->where('status', 'invoiced')->count(),
                    'paid' => $registrations->where('status', 'paid')->count(),
                ],
            ];
        }
        
        return view('livewire.clients.create-edit', compact('stats'));
    }
}
