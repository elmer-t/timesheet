<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Analytics</h1>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Period</label>
                    <select wire:model.live="period" class="form-select">
                        <option value="all_time">All Time</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                @if($period === 'custom')
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" wire:model.live="startDate" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" wire:model.live="endDate" class="form-control">
                    </div>
                @endif

                <div class="col-md-3">
                    <label class="form-label">Client</label>
                    <select wire:model.live="clientFilter" class="form-select">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Project</label>
                    <select wire:model.live="projectFilter" class="form-select">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Metrics --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Hours</h5>
                    <h2 class="mb-0">{{ number_format($totalHours, 2) }}</h2>
                    <small class="text-muted">{{ $totalRegistrations }} registrations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Revenue</h5>
                    <h2 class="mb-0">{{ number_format($totalRevenue, 2) }}</h2>
                    <small class="text-muted">All statuses</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Ready to Invoice</h5>
                    <h2 class="mb-0">{{ number_format($readyToInvoiceStats['revenue'], 2) }}</h2>
                    <small class="text-muted">{{ number_format($readyToInvoiceStats['hours'], 2) }} hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Distance</h5>
                    <h2 class="mb-0">{{ number_format($totalDistance) }}</h2>
                    <small class="text-muted">km</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Breakdown --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Invoiced</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1 text-muted">Hours</p>
                            <h3 class="mb-0">{{ number_format($invoicedStats['hours'], 2) }}</h3>
                        </div>
                        <div class="col-6">
                            <p class="mb-1 text-muted">Revenue</p>
                            <h3 class="mb-0">{{ number_format($invoicedStats['revenue'], 2) }}</h3>
                        </div>
                    </div>
                    <small class="text-muted">{{ $invoicedStats['count'] }} registrations</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Paid</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1 text-muted">Hours</p>
                            <h3 class="mb-0">{{ number_format($paidStats['hours'], 2) }}</h3>
                        </div>
                        <div class="col-6">
                            <p class="mb-1 text-muted">Revenue</p>
                            <h3 class="mb-0">{{ number_format($paidStats['revenue'], 2) }}</h3>
                        </div>
                    </div>
                    <small class="text-muted">{{ $paidStats['count'] }} registrations</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Placeholder --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Overview</h5>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Charts and visualizations will be added here in a future update.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
