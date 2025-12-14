<?php

namespace App\Livewire\Settings;

use App\Models\Currency;
use Livewire\Component;

class Edit extends Component
{
    public $default_currency_id;
    public $project_number_format;
    public $distance_unit;
    public $mileage_allowance;

    protected function rules()
    {
        return [
            'default_currency_id' => 'required|exists:currencies,id',
            'project_number_format' => 'required|string|max:255',
            'distance_unit' => 'required|in:km,mi',
            'mileage_allowance' => 'nullable|numeric|min:0|max:999.99',
        ];
    }

    public function mount()
    {
        $tenant = auth()->user()->tenant;
        $this->default_currency_id = $tenant->default_currency_id;
        $this->project_number_format = $tenant->project_number_format;
        $this->distance_unit = $tenant->distance_unit ?? 'km';
        $this->mileage_allowance = $tenant->mileage_allowance;
    }

    public function save()
    {
        $validated = $this->validate();
        
        $tenant = auth()->user()->tenant;
        $tenant->update($validated);

        session()->flash('success', 'Settings updated successfully.');
    }

    public function render()
    {
        $tenant = auth()->user()->tenant;
        $tenant->load('defaultCurrency', 'users', 'clients', 'projects');
        $currencies = Currency::orderBy('code')->get();
        
        return view('livewire.settings.edit', compact('tenant', 'currencies'));
    }
}
