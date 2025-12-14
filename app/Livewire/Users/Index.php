<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }
        
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }
        
        $user->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    public function sendOnboarding($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }
        
        // Generate a temporary password reset token
        $token = app('auth.password.broker')->createToken($user);
        
        // TODO: Send onboarding email
        session()->flash('success', 'Onboarding email will be sent to ' . $user->email . ' when mail is configured.');
    }

    public function render()
    {
        $users = User::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name')
            ->paginate(15);
        
        return view('livewire.users.index', [
            'users' => $users,
        ]);
    }
}
