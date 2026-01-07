<?php

namespace App\Livewire\Waitlist;

use App\Models\Waitlist;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $confirmingDeletion = false;
    public $waitlistToDelete = null;

    public function confirmDelete($id)
    {
        $this->waitlistToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->waitlistToDelete) {
            $waitlist = Waitlist::find($this->waitlistToDelete);
            
            if ($waitlist) {
                $waitlist->delete();
                session()->flash('message', 'Waitlist entry deleted successfully.');
            }
        }

        $this->confirmingDeletion = false;
        $this->waitlistToDelete = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->waitlistToDelete = null;
    }

    public function render()
    {
        $waitlist = Waitlist::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.waitlist.index', [
            'waitlist' => $waitlist,
        ])->layout('layouts.app')->title('Waitlist');
    }
}
