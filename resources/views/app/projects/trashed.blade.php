@extends('layouts.app')

@section('title', 'Deleted Projects')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Deleted Projects</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('app.projects.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Projects
        </a>
    </div>
</div>

@if($projects->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No deleted projects found.
    </div>
@else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Rate</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->client->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </td>
                                <td>{{ $project->currency->code }} {{ number_format($project->hourly_rate, 2) }}</td>
                                <td>{{ $project->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('app.projects.restore', $project->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to restore this project?')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('app.projects.force-destroy', $project->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will permanently delete this project and cannot be undone!')">
                                            <i class="bi bi-trash"></i> Delete Permanently
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>
@endif
@endsection
