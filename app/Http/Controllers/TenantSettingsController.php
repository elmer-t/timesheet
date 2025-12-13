<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantSettingsController extends Controller
{
    public function edit(): View
    {
        $tenant = auth()->user()->tenant;
        $tenant->load('defaultCurrency', 'users', 'clients', 'projects');
        $currencies = Currency::orderBy('code')->get();
        
        return view('app.settings.edit', compact('tenant', 'currencies'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_currency_id' => 'required|exists:currencies,id',
        ]);

        $tenant = auth()->user()->tenant;
        $tenant->update($validated);

        return redirect()->route('app.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}
