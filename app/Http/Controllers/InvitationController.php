<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function index(): View
    {
        $invitations = Invitation::where('tenant_id', auth()->user()->tenant_id)
            ->with('invitedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('app.invitations.index', compact('invitations'));
    }

    public function create(): View
    {
        return view('app.invitations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:invitations,email,NULL,id,tenant_id,' . auth()->user()->tenant_id,
        ]);

        $invitation = Invitation::create([
            'tenant_id' => auth()->user()->tenant_id,
            'invited_by' => auth()->id(),
            'email' => $validated['email'],
        ]);

        // Send invitation email
        // Mail::to($invitation->email)->send(new InvitationMail($invitation));

        return redirect()->route('app.invitations.index')
            ->with('success', 'Invitation sent successfully.');
    }

    public function destroy(Invitation $invitation): RedirectResponse
    {
        // Ensure invitation belongs to user's tenant
        if ($invitation->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $invitation->delete();

        return redirect()->route('app.invitations.index')
            ->with('success', 'Invitation deleted successfully.');
    }
}
