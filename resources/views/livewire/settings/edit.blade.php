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
                        <dd class="col-sm-9">
                            @php
                                $userCount = $tenant->users->count();
                                $userLimit = $tenant->user_limit;
                                $userUsage = $userLimit > 0 ? ($userCount / $userLimit) * 100 : 0;
                                $userBadge = $userUsage >= 100 ? 'danger' : ($userUsage >= 80 ? 'warning' : 'success');
                            @endphp
                            <span class="badge bg-{{ $userBadge }}">{{ $userCount }}</span> / {{ $userLimit }}
                            <small class="text-muted">({{ $userLimit - $userCount }} remaining)</small>
                        </dd>
                        <dt class="col-sm-3">Clients</dt>
                        <dd class="col-sm-9">
                            @php
                                $clientCount = $tenant->clients->count();
                                $clientLimit = $tenant->client_limit;
                                $clientUsage = $clientLimit > 0 ? ($clientCount / $clientLimit) * 100 : 0;
                                $clientBadge = $clientUsage >= 100 ? 'danger' : ($clientUsage >= 80 ? 'warning' : 'success');
                            @endphp
                            <span class="badge bg-{{ $clientBadge }}">{{ $clientCount }}</span> / {{ $clientLimit }}
                            <small class="text-muted">({{ $clientLimit - $clientCount }} remaining)</small>
                        </dd>
                        <dt class="col-sm-3">Projects</dt>
                        <dd class="col-sm-9">
                            @php
                                $projectCount = $tenant->projects->count();
                                $projectLimit = $tenant->project_limit;
                                $projectUsage = $projectLimit > 0 ? ($projectCount / $projectLimit) * 100 : 0;
                                $projectBadge = $projectUsage >= 100 ? 'danger' : ($projectUsage >= 80 ? 'warning' : 'success');
                            @endphp
                            <span class="badge bg-{{ $projectBadge }}">{{ $projectCount }}</span> / {{ $projectLimit }}
                            <small class="text-muted">({{ $projectLimit - $projectCount }} remaining)</small>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Settings</h5>
                    <form wire:submit="save">
                        <div class="mb-3 row">
                            <label for="default_currency_id" class="col-sm-3 col-form-label">Default Currency *</label>
                            <div class="col-sm-9">
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
                        </div>

                        <div class="mb-3 row">
                            <label for="project_number_format" class="col-sm-3 col-form-label">Project Numbers *</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('project_number_format') is-invalid @enderror" 
                                       id="project_number_format" wire:model="project_number_format" required>
                                @error('project_number_format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Use {YYYY} and {####} placeholders. Example: PROJ-{YYYY}-{####}</small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="distance_unit" class="col-sm-3 col-form-label">Distance Unit *</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('distance_unit') is-invalid @enderror" 
                                        id="distance_unit" wire:model="distance_unit" required>
                                    <option value="km">Kilometers (km)</option>
                                    <option value="mi">Miles (mi)</option>
                                </select>
                                @error('distance_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Save Settings</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Saving...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
