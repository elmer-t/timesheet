<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Trashed extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function restore($id)
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->restore();
        
        session()->flash('success', 'Client restored successfully.');
    }

    public function forceDelete($id)
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->forceDelete();
        
        session()->flash('success', 'Client permanently deleted.');
    }

    public function render()
    {
        $clients = Client::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(15);
        
        return view('livewire.clients.trashed', [
            'clients' => $clients,
        ]);
    }
}
