<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">{{ $userId ? 'Edit' : 'Create' }} User</h1>
        </div>
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
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" wire:model="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password {{ $userId ? '(leave blank to keep current)' : '*' }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" wire:model="password" {{ $userId ? '' : 'required' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password {{ $userId ? '' : '*' }}</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" wire:model="password_confirmation" {{ $userId ? '' : 'required' }}>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_admin" wire:model="is_admin">
                            <label class="form-check-label" for="is_admin">
                                Administrator
                            </label>
                            <small class="form-text text-muted d-block">Administrators can manage clients, projects, and users.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $userId ? 'Update' : 'Create' }} User</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Saving...
                                </span>
                            </button>
                            <a href="{{ route('app.users.index') }}" class="btn btn-secondary" wire:navigate>Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
