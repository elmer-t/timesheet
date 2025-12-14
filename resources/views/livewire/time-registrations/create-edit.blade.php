<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">{{ $registrationId ? 'Edit' : 'Create' }} Time Registration</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date *</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" wire:model="date" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="client_id" class="form-label">Client *</label>
                            <select class="form-select @error('client_id') is-invalid @enderror" 
                                    id="client_id" wire:model.live="client_id" required>
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="project_id" class="form-label">Project</label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" wire:model="project_id">
                                <option value="">Select a project (optional)</option>
                                @foreach($filteredProjects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->client->name }})</option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional - leave blank for general time tracking</small>
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (hours) *</label>
                            <input type="number" step="0.25" class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" wire:model="duration" min="0.25" max="24" required>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">In hours, e.g., 1.5 for 1 hour 30 minutes</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" wire:model="description" rows="3"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" wire:model="location" placeholder="e.g., Office, Client Site">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="distance" class="form-label">Distance ({{ Auth::user()->tenant->distance_unit }})</label>
                                <input type="number" step="1" class="form-control @error('distance') is-invalid @enderror" 
                                       id="distance" wire:model="distance" min="0" placeholder="0">
                                @error('distance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" wire:model="status" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $registrationId ? 'Update' : 'Create' }} Registration</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Saving...
                                </span>
                            </button>
                            <a href="{{ route('app.registrations.index') }}" class="btn btn-secondary" wire:navigate>Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
