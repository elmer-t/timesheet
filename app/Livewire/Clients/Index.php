<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function deleteClient($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        
        session()->flash('success', 'Client deleted successfully.');
    }

    public function render()
    {
        $clients = Client::orderBy('name')->paginate(15);
        
        return view('livewire.clients.index', [
            'clients' => $clients,
        ]);
    }
}
