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
                    <strong>Total Hours:</strong> {{ number_format($totalHours, 2) }} | 
                    <strong>Total Distance:</strong> {{ $totalDistance }} {{ Auth::user()->tenant->distance_unit }} | 
                    <strong>Total Revenue:</strong> {{ number_format($totalRevenue, 2) }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('app.timesheets.print', ['client_id' => $selectedClientId, 'period' => $period, 'date' => $date]) }}" 
                       class="btn btn-sm btn-primary" target="_blank"
                       onclick="return confirm('This will mark all ready-to-invoice registrations as invoiced. Continue?');">
                        <i class="bi bi-printer"></i> Generate Invoice & Print
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>Distance</th>
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
                                    <td>{{ $registration->location ?? '-' }}</td>
                                    <td>
                                        @if($registration->distance)
                                            {{ $registration->distance }} {{ Auth::user()->tenant->distance_unit }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ number_format($registration->duration, 2) }}h</td>
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
