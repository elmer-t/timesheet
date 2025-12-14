<?php
// Script to create remaining Livewire views (Users, Dashboard, Timesheets, Settings)

$basePath = __DIR__ . '/resources/views/livewire';

$views = [
    // Users
    'users/index.blade.php' => <<<'VIEW'
<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Team Members</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.users.create') }}" class="btn btn-primary" wire:navigate>
                <i class="bi bi-plus-circle"></i> New User
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_admin)
                                            <span class="badge bg-primary">Admin</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('app.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" wire:navigate>
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    wire:click="sendOnboarding({{ $user->id }})">
                                                <i class="bi bi-envelope"></i> Send Onboarding
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    wire:click="deleteUser({{ $user->id }})"
                                                    wire:confirm="Are you sure you want to delete this user?">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $users->links() }}
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people-fill text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No team members yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
VIEW,

    'users/create-edit.blade.php' => <<<'VIEW'
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
VIEW,

    'dashboard.blade.php' => <<<'VIEW'
<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Dashboard</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Hours</h5>
                    <h2 class="mb-0">{{ number_format($totalHours, 1) }}</h2>
                    <small class="text-muted">{{ $totalRegistrations }} registrations</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Revenue</h5>
                    <h2 class="mb-0">{{ number_format($totalRevenue, 2) }}</h2>
                    <small class="text-muted">All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Ready to Invoice</h5>
                    <h2 class="mb-0">{{ number_format($readyToInvoiceStats['revenue'], 2) }}</h2>
                    <small class="text-muted">{{ number_format($readyToInvoiceStats['hours'], 1) }} hours</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Invoiced</h5>
                    <p class="mb-1">Hours: {{ number_format($invoicedStats['hours'], 1) }}</p>
                    <p class="mb-0">Revenue: {{ number_format($invoicedStats['revenue'], 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Paid</h5>
                    <p class="mb-1">Hours: {{ number_format($paidStats['hours'], 1) }}</p>
                    <p class="mb-0">Revenue: {{ number_format($paidStats['revenue'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Recent Time Registrations</h5>
            @if($monthRegistrations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Project</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthRegistrations->take(10) as $registration)
                                <tr>
                                    <td>{{ $registration->date->format('M d') }}</td>
                                    <td>{{ $registration->client->name }}</td>
                                    <td>{{ $registration->project?->name ?? '-' }}</td>
                                    <td>{{ number_format($registration->duration, 1) }}h</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No registrations for this month.</p>
            @endif
        </div>
    </div>
</div>
VIEW,

    'timesheets/index.blade.php' => <<<'VIEW'
<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Timesheets</h1>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="client_id" class="form-label">Client</label>
                    <select class="form-select" id="client_id" wire:model.live="selectedClientId">
                        <option value="">Select a client...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="period" class="form-label">Period</label>
                    <select class="form-select" id="period" wire:model.live="period">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" wire:model.live="date">
                </div>
            </div>
        </div>
    </div>

    @if($selectedClientId && $registrations->count() > 0)
        <div class="card mb-4">
            <div class="card-body">
                <h5>{{ $periodLabel }}</h5>
                <p class="mb-0">
                    <strong>Total Hours:</strong> {{ number_format($totalHours, 1) }} | 
                    <strong>Total Revenue:</strong> {{ number_format($totalRevenue, 2) }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('app.timesheets.print', ['client_id' => $selectedClientId, 'period' => $period, 'date' => $date]) }}" 
                       class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="bi bi-printer"></i> Print
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Description</th>
                                <th>Duration</th>
                                <th>Rate</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->date->format('M d, Y') }}</td>
                                    <td>{{ $registration->project?->name ?? '-' }}</td>
                                    <td>{{ Str::limit($registration->description, 50) }}</td>
                                    <td>{{ number_format($registration->duration, 1) }}h</td>
                                    <td>
                                        @if($registration->project)
                                            {{ $registration->project->currency->code }} {{ number_format($registration->project->hourly_rate, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($registration->project)
                                            {{ $registration->project->currency->code }} {{ number_format($registration->revenue, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($selectedClientId)
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="text-muted">No time registrations found for the selected period.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Select a client to view timesheet.</p>
            </div>
        </div>
    @endif
</div>
VIEW,

    'settings/edit.blade.php' => <<<'VIEW'
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
                            <small class="text-muted">Use {year} and {number} placeholders. Example: PRJ-{year}-{number}</small>
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
VIEW,

];

foreach ($views as $path => $content) {
    $fullPath = $basePath . '/' . $path;
    $dir = dirname($fullPath);
    
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($fullPath, $content);
    echo "Created: $path\n";
}

echo "\nAll remaining Livewire views created successfully!\n";
