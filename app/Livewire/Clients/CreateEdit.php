<?php

namespace App\Livewire\Clients;

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
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ];
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
        return view('livewire.clients.create-edit');
    }
}
