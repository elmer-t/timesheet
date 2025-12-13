@extends('layouts.app')

@section('title', 'Time Registrations')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Time Registrations</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('app.registrations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Registration
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($registrations->count() > 0)
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                            <tr>
                                <td>{{ $registration->date->format('M d, Y') }}</td>
                                <td>{{ $registration->client->name }}</td>
                                <td>{{ $registration->project->name }}</td>
                                <td>{{ number_format($registration->duration, 1) }}h</td>
                                <td>{{ $registration->project->currency->code }} {{ number_format($registration->revenue, 2) }}</td>
                                <td>{{ Str::limit($registration->description, 50) }}</td>
                                <td>
                                    <a href="{{ route('app.registrations.edit', $registration) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('app.registrations.destroy', $registration) }}" 
                                          class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $registrations->links() }}
        @else
            <div class="text-center py-5">
                <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No time registrations yet. Create your first one!</p>
            </div>
        @endif
    </div>
</div>
@endsection
