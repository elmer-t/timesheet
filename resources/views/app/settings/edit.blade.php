@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Organization Settings</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('app.settings.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Organization Name</label>
                        <input type="text" class="form-control" value="{{ $tenant->name }}" disabled>
                        <small class="text-muted">Contact support to change your organization name</small>
                    </div>

                    <div class="mb-3">
                        <label for="default_currency_id" class="form-label">Default Currency for New Projects *</label>
                        <select class="form-select @error('default_currency_id') is-invalid @enderror" 
                                id="default_currency_id" name="default_currency_id" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" 
                                        {{ old('default_currency_id', $tenant->default_currency_id) == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->code }} - {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('default_currency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">This currency will be pre-selected when creating new projects</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> <strong>Note:</strong> Changing the default currency will not affect existing projects. Each project can have its own currency.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                        <a href="{{ route('app.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Organization Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong><br>{{ $tenant->name }}</p>
                <p><strong>Default Currency:</strong><br>{{ $tenant->defaultCurrency->code }} ({{ $tenant->defaultCurrency->name }})</p>
                <p><strong>Total Users:</strong><br>{{ $tenant->users->count() }}</p>
                <p><strong>Total Clients:</strong><br>{{ $tenant->clients->count() }}</p>
                <p><strong>Total Projects:</strong><br>{{ $tenant->projects->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
