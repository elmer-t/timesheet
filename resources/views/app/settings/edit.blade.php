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
                        <small class="text-muted">Changing the default currency will <i>not</i> affect existing projects</small>
                    </div>

                    <div class="mb-3">
                        <label for="project_number_format" class="form-label">Project Number Format *</label>
                        <input type="text" 
                               class="form-control @error('project_number_format') is-invalid @enderror" 
                               id="project_number_format" 
                               name="project_number_format" 
                               value="{{ old('project_number_format', $tenant->project_number_format) }}" 
                               required>
                        @error('project_number_format')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Use <code>yyyy</code> for year and <code>nnnn</code> for auto-incrementing number.<br>
                            Example: <code>PR-yyyy-nnnn</code> generates PR-2025-0001, PR-2025-0002, etc.
                        </small>
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
