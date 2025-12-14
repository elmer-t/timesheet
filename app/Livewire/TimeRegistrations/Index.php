<?php

namespace App\Livewire\TimeRegistrations;

use App\Models\TimeRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function deleteRegistration($id)
    {
        $registration = TimeRegistration::findOrFail($id);
        
        $this->authorize('delete', $registration);
        
        $registration->delete();
        
        session()->flash('success', 'Time registration deleted successfully.');
    }

    public function render()
    {
        $registrations = TimeRegistration::with(['client', 'project.currency'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('livewire.time-registrations.index', [
            'registrations' => $registrations,
        ]);
    }
}
