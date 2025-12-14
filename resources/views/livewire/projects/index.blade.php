<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Projects</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.projects.trashed') }}" class="btn btn-outline-secondary me-2" wire:navigate>
                <i class="bi bi-trash"></i> View Deleted
            </a>
            <a href="{{ route('app.projects.create') }}" class="btn btn-primary" wire:navigate>
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
                                <th>Number</th>
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
                                    <td>
                                        <a href="{{ route('app.projects.edit', $project) }}" class="text-decoration-none" wire:navigate>
                                            <code>{{ $project->project_number }}</code>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('app.projects.edit', $project) }}" class="text-decoration-none" wire:navigate>
                                            <strong>{{ $project->name }}</strong>
                                        </a>
                                    </td>
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
                                        <a href="{{ route('app.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary" wire:navigate>
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteProject('{{ $project->id }}')"
                                                wire:confirm="Are you sure you want to delete this project?">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
</div>