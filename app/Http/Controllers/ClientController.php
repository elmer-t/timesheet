<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::orderBy('name')->paginate(15);
        return view('app.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('app.clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Client::create($validated);

        return redirect()->route('app.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function edit(Client $client): View
    {
        return view('app.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('app.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('app.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
