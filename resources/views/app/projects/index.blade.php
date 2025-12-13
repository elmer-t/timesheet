@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Projects</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('app.projects.trashed') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-trash"></i> View Deleted
        </a>
        <a href="{{ route('app.projects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Project
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($projects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Period</th>
                            <th>Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td><strong>{{ $project->name }}</strong></td>
                                <td>{{ $project->client->name }}</td>
                                <td>
                                    @if($project->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($project->status === 'completed')
                                        <span class="badge bg-secondary">Completed</span>
                                    @else
                                        <span class="badge bg-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $project->start_date->format('M d, Y') }}
                                    @if($project->end_date)
                                        - {{ $project->end_date->format('M d, Y') }}
                                    @endif
                                </td>
                                <td>{{ $project->currency->code }} {{ number_format($project->hourly_rate, 2) }}</td>
                                <td>
                                    <a href="{{ route('app.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('app.projects.destroy', $project) }}" 
                                          class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $projects->links() }}
        @else
            <div class="text-center py-5">
                <i class="bi bi-briefcase text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No projects yet. Create your first project to get started!</p>
            </div>
        @endif
    </div>
</div>
@endsection
