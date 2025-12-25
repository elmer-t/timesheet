<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">{{ $projectId ? 'Edit' : 'Create New' }} Project</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
				<div class="card-header">
					<h5 class="mb-0">{{ $projectId ? ($project->project_number ?? 'N/A') : 'New Project' }}</h5>
				</div>
                <div class="card-body">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Client *</label>
                            <select class="form-select @error('client_id') is-invalid @enderror" 
                                    id="client_id" wire:model="client_id" required>
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
                            <label for="name" class="form-label">Project Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" wire:model="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" wire:model="description" rows="3"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

						<div class="col my-5"></div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="status" class="form-label">Status *</label>
								<select class="form-select @error('status') is-invalid @enderror" 
										id="status" wire:model="status" required>
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
									<option value="completed">Completed</option>
								</select>
								@error('status')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
								<small class="form-text text-muted d-block">Only active projects can be selected for time registrations.</small>
							</div>

							<div class="col-md-6 mb-3">
								<div class="form-check">
									<input class="form-check-input @error('is_paid') is-invalid @enderror" 
										type="checkbox" 
										id="is_paid" 
										wire:model="is_paid">
									<label class="form-check-label" for="is_paid">
										Paid Project
									</label>
									@error('is_paid')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
									<small class="form-text text-muted d-block">Time registrations for non-paid projects will automatically be marked as Non-paid.</small>
								</div>
							</div>
						</div>

						<div class="col my-5"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" wire:model="start_date" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" wire:model="end_date">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

						<div class="col my-5"></div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="currency_id" class="form-label">Currency *</label>
                                <select class="form-select @error('currency_id') is-invalid @enderror" 
                                        id="currency_id" wire:model="currency_id" required>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate *</label>
                                <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                       id="hourly_rate" wire:model="hourly_rate" required>
                                @error('hourly_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
							
							<div class="col-md-4 mb-3">
								<label for="mileage_allowance" class="form-label">Mileage Allowance</label>
								<input type="number" step="0.01" class="form-control @error('mileage_allowance') is-invalid @enderror" 
								id="mileage_allowance" wire:model="mileage_allowance" min="0" max="999.99" placeholder="0.00">
								@error('mileage_allowance')
                                <div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $projectId ? 'Update' : 'Create' }} Project</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Saving...
                                </span>
                            </button>
                            <a href="{{ route('app.projects.index') }}" class="btn btn-secondary" wire:navigate>Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        @if($projectId && $stats)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Project Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Hours</small>
                            <h4 class="mb-0">{{ floor($stats['total_hours'] / 8) }}d {{ floor($stats['total_hours'] % 8) }}h {{ round((($stats['total_hours'] * 60) % 60)) }}m</h4>
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
                            <small class="text-muted d-block">Mileage Reimbursement</small>
                            <h4 class="mb-0">{{ number_format($stats['mileage_reimbursement'], 2) }}</h4>
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