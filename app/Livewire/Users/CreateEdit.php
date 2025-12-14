<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class CreateEdit extends Component
{
    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $is_admin = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'is_admin' => 'boolean',
        ];

        if ($this->userId) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $this->userId;
            if ($this->password) {
                $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
            }
        } else {
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }

    public function mount($id = null)
    {
        if ($id) {
            $user = User::findOrFail($id);
            
            if ($user->tenant_id !== auth()->user()->tenant_id) {
                abort(403);
            }
            
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->is_admin = $user->is_admin;
        }
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            
            if ($user->tenant_id !== auth()->user()->tenant_id) {
                abort(403);
            }
            
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_admin' => $this->is_admin,
            ]);
            
            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }
            
            $message = 'User updated successfully.';
        } else {
            User::create([
                'tenant_id' => auth()->user()->tenant_id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($this->password),
                'is_admin' => $this->is_admin,
            ]);
            $message = 'User created successfully.';
        }

        session()->flash('success', $message);
        return redirect()->route('app.users.index');
    }

    public function render()
    {
        return view('livewire.users.create-edit');
    }
}
