<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">{{ $clientId ? 'Edit' : 'Create New' }} Client</h1>
        </div>
        @if($clientId)
            <div class="col-auto">
                <a href="{{ route('app.projects.create', ['client_id' => $clientId]) }}" class="btn btn-success" wire:navigate>
                    <i class="bi bi-plus-circle"></i> New Project
                </a>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" wire:model="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" wire:model="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" wire:model="phone">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" wire:model="address" rows="3"></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $clientId ? 'Update' : 'Create' }} Client</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Saving...
                                </span>
                            </button>
                            <a href="{{ route('app.clients.index') }}" class="btn btn-secondary" wire:navigate>Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        @if($clientId && $stats)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Client Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Projects</small>
                            <h4 class="mb-0">{{ $stats['total_projects'] }}</h4>
                            <small class="text-muted">{{ $stats['active_projects'] }} active</small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Hours</small>
                            <h4 class="mb-0">{{ number_format($stats['total_hours'], 2) }}</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Revenue</small>
                            <h4 class="mb-0">{{ number_format($stats['total_revenue'], 2) }}</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Distance</small>
                            <h4 class="mb-0">{{ number_format($stats['total_distance']) }} km</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Time Registrations</small>
                            <h4 class="mb-0">{{ $stats['total_registrations'] }}</h4>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <small class="text-muted">Ready to Invoice:</small>
                            <strong>{{ $stats['by_status']['ready_to_invoice'] }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Invoiced:</small>
                            <strong>{{ $stats['by_status']['invoiced'] }}</strong>
                        </div>
                        <div>
                            <small class="text-muted">Paid:</small>
                            <strong>{{ $stats['by_status']['paid'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
