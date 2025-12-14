<?php

namespace App\Http\Controllers;

use App\Models\TimeRegistration;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
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
        
        // Get last used client and project from session
        $lastClientId = session('last_client_id');
        $lastProjectId = session('last_project_id');
        
        return view('app.registrations.create', compact('clients', 'projects', 'lastClientId', 'lastProjectId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(TimeRegistration::getStatuses())),
        ]);

        // Verify project can accept time registration if project is provided
        if (!empty($validated['project_id'])) {
            $project = Project::findOrFail($validated['project_id']);
            if (!$project->canRegisterTime()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'errors' => ['project_id' => ['This project is not available for time registration.']]
                    ], 422);
                }
                return back()->withErrors(['project_id' => 'This project is not available for time registration.']);
            }
        }

        $validated['user_id'] = auth()->id();

        TimeRegistration::create($validated);

        // Remember last used client and project
        session([
            'last_client_id' => $validated['client_id'],
            'last_project_id' => $validated['project_id'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time registration created successfully.'
            ]);
        }

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

    public function show(Request $request, TimeRegistration $registration)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'id' => $registration->id,
                'client_id' => $registration->client_id,
                'project_id' => $registration->project_id,
                'date' => $registration->date,
                'duration' => $registration->duration,
                'description' => $registration->description,
                'status' => $registration->status,
            ]);
        }

        return redirect()->route('app.registrations.edit', $registration);
    }

    public function update(Request $request, TimeRegistration $registration)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(TimeRegistration::getStatuses())),
        ]);

        $registration->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time registration updated successfully.'
            ]);
        }

        return redirect()->route('app.registrations.index')
            ->with('success', 'Time registration updated successfully.');
    }

    public function destroy(Request $request, TimeRegistration $registration)
    {
        $registration->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time registration deleted successfully.'
            ]);
        }

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
