@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Dashboard</h1>
        <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Registrations</h6>
                        <h2 class="mb-0">{{ $totalRegistrations }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Hours</h6>
                        <h2 class="mb-0">{{ number_format($totalHours, 1) }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-hourglass-split" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Revenue</h6>
                        <h2 class="mb-0">{{ auth()->user()->tenant->defaultCurrency->code }} {{ number_format($totalRevenue, 2) }}</h2>
                    </div>
                    <div class="text-info">
						<i class="bi bi-cash-coin" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Time Registrations</h5>
                <a href="{{ route('app.registrations.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> New Registration
                </a>
            </div>
            <div class="card-body">
                @if($recentRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Project</th>
                                    <th>Duration</th>
                                    <th>Revenue</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRegistrations as $registration)
                                    <tr>
                                        <td>{{ $registration->date->format('M d, Y') }}</td>
                                        <td>{{ $registration->client->name }}</td>
                                        <td>{{ $registration->project->name }}</td>
                                        <td>{{ number_format($registration->duration, 1) }}h</td>
                                        <td>{{ $registration->project->currency->code }} {{ number_format($registration->revenue, 2) }}</td>
                                        <td>{{ Str::limit($registration->description, 50) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No time registrations yet. Get started by creating your first one!</p>
                        <a href="{{ route('app.registrations.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create First Registration
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
