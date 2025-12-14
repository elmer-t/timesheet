<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name')
            ->paginate(15);
        
        return view('app.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('app.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('app.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        // Ensure user belongs to tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('app.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        // Ensure user belongs to tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('app.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Ensure user belongs to tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('app.users.index')
                ->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('app.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function sendOnboarding(User $user): RedirectResponse
    {
        // Ensure user belongs to tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Generate a temporary password reset token
        $token = app('auth.password.broker')->createToken($user);

        // TODO: Send onboarding email
        // Mail::to($user->email)->send(new OnboardingMail($user, $token));

        return redirect()->route('app.users.index')
            ->with('success', 'Onboarding email will be sent to ' . $user->email . ' when mail is configured.');
    }
}
