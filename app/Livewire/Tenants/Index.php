<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $tenants = Tenant::withCount(['users', 'clients', 'projects'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.tenants.index', [
            'tenants' => $tenants,
        ])->layout('layouts.app')->title('Tenants');
    }
}
