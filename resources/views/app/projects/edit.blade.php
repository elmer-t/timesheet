@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Edit Project</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('app.projects.update', $project) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client *</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                id="client_id" name="client_id" required>
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $project->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $project->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="currency_id" class="form-label">Currency *</label>
                            <select class="form-select @error('currency_id') is-invalid @enderror" 
                                    id="currency_id" name="currency_id" required>
                                <option value="">Select currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" 
                                            {{ old('currency_id', $project->currency_id) == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate *</label>
                            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $project->hourly_rate) }}" required>
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Project</button>
                        <a href="{{ route('app.projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
