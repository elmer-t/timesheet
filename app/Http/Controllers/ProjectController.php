<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with(['client', 'currency'])->orderBy('name')->paginate(15);
        return view('app.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = auth()->user()->tenant->default_currency_id;
        return view('app.projects.create', compact('clients', 'currencies', 'defaultCurrencyId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'required|exists:currencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Project::create($validated);

        return redirect()->route('app.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        $clients = Client::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        return view('app.projects.edit', compact('project', 'clients', 'currencies'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'required|exists:currencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        $project->update($validated);

        return redirect()->route('app.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('app.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function trashed(): View
    {
        $projects = Project::onlyTrashed()->with(['client', 'currency'])->orderBy('deleted_at', 'desc')->paginate(15);
        return view('app.projects.trashed', compact('projects'));
    }

    public function restore(string $id): RedirectResponse
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();

        return redirect()->route('app.projects.trashed')
            ->with('success', 'Project restored successfully.');
    }

    public function forceDestroy(string $id): RedirectResponse
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->forceDelete();

        return redirect()->route('app.projects.trashed')
            ->with('success', 'Project permanently deleted.');
    }
}
