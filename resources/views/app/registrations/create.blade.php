@extends('layouts.app')

@section('title', 'Create Time Registration')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Create Time Registration</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('app.registrations.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="date" class="form-label">Date *</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" 
                               id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client *</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                id="client_id" name="client_id" required>
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project *</label>
                        <select class="form-select @error('project_id') is-invalid @enderror" 
                                id="project_id" name="project_id" required>
                            <option value="">Select a project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" 
                                        data-client-id="{{ $project->client_id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }} ({{ $project->client->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (hours) *</label>
                        <input type="number" step="0.25" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration') }}" min="0.25" max="24" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">In hours, e.g., 1.5 for 1 hour 30 minutes</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Registration</button>
                        <a href="{{ route('app.registrations.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('client_id');
    const projectSelect = document.getElementById('project_id');
    const allProjects = Array.from(projectSelect.options);

    clientSelect.addEventListener('change', function() {
        const selectedClientId = this.value;
        
        // Clear and reset project select
        projectSelect.innerHTML = '<option value="">Select a project</option>';
        
        if (selectedClientId) {
            // Filter projects by selected client
            allProjects.forEach(option => {
                if (option.dataset.clientId === selectedClientId) {
                    projectSelect.appendChild(option.cloneNode(true));
                }
            });
        } else {
            // Show all projects
            allProjects.forEach(option => {
                projectSelect.appendChild(option.cloneNode(true));
            });
        }
    });
});
</script>
@endpush
