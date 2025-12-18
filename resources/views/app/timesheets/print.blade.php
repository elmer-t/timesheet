<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timesheet - {{ $client->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h1>Timesheet</h1>
                <p class="text-muted">{{ $periodLabel }}</p>
                <h3>{{ $client->name }}</h3>
            </div>
            <div class="col-auto mt-5">
                <p><strong>{{ auth()->user()->tenant->name }}</strong></p>
                <p>{{ auth()->user()->name }}</p>
            </div>
        </div>

        @if($client->address)
            <div class="row mb-4">
                <div class="col">
                    <p>{!! nl2br(e($client->address)) !!}</p>
                </div>
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr class="table-light">
                    <th>Date</th>
                    <th>Project</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Distance</th>
                    <th>Hours</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $registration)
                    <tr>
                        <td>{{ $registration->date->format('M d, Y') }}</td>
                        <td>
                            @if($registration->project)
                                {{ $registration->project->name }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $registration->description }}</td>
                        <td>{{ $registration->location ?? '-' }}</td>
                        <td>
                            @if($registration->distance)
                                {{ $registration->distance }} {{ auth()->user()->tenant->distance_unit }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ number_format($registration->duration, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-active fw-bold">
                    <td colspan="4" class="text-end">Total:</td>
                    <td>{{ $totalDistance }} {{ auth()->user()->tenant->distance_unit }}</td>
                    <td>{{ number_format($totalHours, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-5 no-print">
            <div class="col text-center">
                <button onclick="window.print()" class="btn btn-primary">Print Timesheet</button>
                <a href="{{ route('app.timesheets.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row mt-5" style="page-break-inside: avoid;">
            <div class="col-6">
                <p class="mb-0">__________________________________</p>
                <p class="text-muted">Signature</p>
            </div>
            <div class="col-6">
                <p class="mb-0">Date: {{ date('M d, Y') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
