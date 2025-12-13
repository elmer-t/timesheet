<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistration;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimeRegistrationController extends Controller
{
    public function index(): View
    {
        $registrations = TimeRegistration::with(['client', 'project.currency'])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('app.registrations.index', compact('registrations'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        $projects = Project::availableForRegistration()
            ->with('client')
            ->orderBy('name')
            ->get();
        
        return view('app.registrations.create', compact('clients', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string',
        ]);

        // Verify project can accept time registration
        $project = Project::findOrFail($validated['project_id']);
        if (!$project->canRegisterTime()) {
            return back()->withErrors(['project_id' => 'This project is not available for time registration.']);
        }

        $validated['user_id'] = auth()->id();

        TimeRegistration::create($validated);

        return redirect()->route('app.registrations.index')
            ->with('success', 'Time registration created successfully.');
    }

    public function edit(TimeRegistration $registration): View
    {
        $clients = Client::orderBy('name')->get();
        $projects = Project::availableForRegistration()
            ->with('client')
            ->orderBy('name')
            ->get();
        
        return view('app.registrations.edit', compact('registration', 'clients', 'projects'));
    }

    public function update(Request $request, TimeRegistration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string',
        ]);

        $registration->update($validated);

        return redirect()->route('app.registrations.index')
            ->with('success', 'Time registration updated successfully.');
    }

    public function destroy(TimeRegistration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()->route('app.registrations.index')
            ->with('success', 'Time registration deleted successfully.');
    }

    /**
     * Get projects for a specific client (AJAX)
     */
    public function getProjectsByClient(Client $client)
    {
        $projects = $client->projects()
            ->availableForRegistration()
            ->orderBy('name')
            ->get(['id', 'name', 'hourly_rate']);

        return response()->json($projects);
    }
}
