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
            <div class="card-body">
                <x-calendar 
                    :month="$month" 
                    :year="$year" 
                    :registrations="$monthRegistrations"
                    :show-modal="true"
                />
            </div>
        </div>
    </div>
</div>
@endsection
