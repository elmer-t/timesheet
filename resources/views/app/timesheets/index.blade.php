@extends('layouts.app')

@section('title', 'Timesheets')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Timesheets</h1>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('app.timesheets.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="client_id" class="form-label">Client</label>
                <select class="form-select" id="client_id" name="client_id">
                    <option value="">Select a client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $selectedClientId == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="period" class="form-label">Period</label>
                <select class="form-select" id="period" name="period">
                    <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Day</option>
                    <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Month</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

@if($selectedClientId && $registrations->count() > 0)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $periodLabel }}</h5>
            <a href="{{ route('app.timesheets.print', ['client_id' => $selectedClientId, 'period' => $period, 'date' => $date]) }}" 
               class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="bi bi-printer"></i> Print
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Description</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                            <tr>
                                <td>{{ $registration->date->format('M d, Y') }}</td>
                                <td>{{ $registration->project->name }}</td>
                                <td>{{ $registration->description }}</td>
                                <td>{{ number_format($registration->duration, 1) }}h</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active fw-bold">
                            <td colspan="3">Total</td>
                            <td>{{ number_format($totalHours, 1) }}h</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@elseif($selectedClientId)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">No time registrations found for the selected criteria.</p>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">Select a client and period to view timesheet.</p>
        </div>
    </div>
@endif
@endsection
