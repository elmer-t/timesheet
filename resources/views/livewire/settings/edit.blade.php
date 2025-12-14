<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Tenant Settings</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tenant Information</h5>
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $tenant->name }}</dd>
                        <dt class="col-sm-3">Created</dt>
                        <dd class="col-sm-9">{{ $tenant->created_at->format('F d, Y') }}</dd>
                        <dt class="col-sm-3">Team Members</dt>
                        <dd class="col-sm-9">{{ $tenant->users->count() }}</dd>
                        <dt class="col-sm-3">Clients</dt>
                        <dd class="col-sm-9">{{ $tenant->clients->count() }}</dd>
                        <dt class="col-sm-3">Projects</dt>
                        <dd class="col-sm-9">{{ $tenant->projects->count() }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Settings</h5>
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label for="default_currency_id" class="form-label">Default Currency *</label>
                            <select class="form-select @error('default_currency_id') is-invalid @enderror" 
                                    id="default_currency_id" wire:model="default_currency_id" required>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}</option>
                                @endforeach
                            </select>
                            @error('default_currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="project_number_format" class="form-label">Project Number Format *</label>
                            <input type="text" class="form-control @error('project_number_format') is-invalid @enderror" 
                                   id="project_number_format" wire:model="project_number_format" required>
                            @error('project_number_format')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Use {YYYY} and {####} placeholders. Example: PROJ-{YYYY}-{####}</small>
                        </div>

                        <div class="mb-3">
                            <label for="distance_unit" class="form-label">Distance Unit *</label>
                            <select class="form-select @error('distance_unit') is-invalid @enderror" 
                                    id="distance_unit" wire:model="distance_unit" required>
                                <option value="km">Kilometers (km)</option>
                                <option value="mi">Miles (mi)</option>
                            </select>
                            @error('distance_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mileage_allowance" class="form-label">Mileage Allowance (per {{ $distance_unit }})</label>
                            <input type="number" step="0.01" class="form-control @error('mileage_allowance') is-invalid @enderror" 
                                   id="mileage_allowance" wire:model="mileage_allowance" min="0" max="999.99" placeholder="0.00">
                            @error('mileage_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Rate for mileage reimbursement</small>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Save Settings</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
