<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Deleted Projects</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.projects.index') }}" class="btn btn-outline-secondary" wire:navigate>
                <i class="bi bi-arrow-left"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($projects->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Project</th>
                                <th>Client</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td><code>{{ $project->project_number }}</code></td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->client->name }}</td>
                                    <td>{{ $project->deleted_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                wire:click="restore({{ $project->id }})"
                                                wire:confirm="Are you sure you want to restore this project?">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="forceDelete({{ $project->id }})"
                                                wire:confirm="Are you sure you want to permanently delete this project? This cannot be undone!">
                                            <i class="bi bi-x-circle"></i> Delete Permanently
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
                    <p class="text-muted">No deleted projects.</p>
                </div>
            @endif
        </div>
    </div>
</div>