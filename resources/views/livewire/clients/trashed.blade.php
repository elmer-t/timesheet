<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Deleted Clients</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.clients.index') }}" class="btn btn-outline-secondary" wire:navigate>
                <i class="bi bi-arrow-left"></i> Back to Clients
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->deleted_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                wire:click="restore({{ $client->id }})"
                                                wire:confirm="Are you sure you want to restore this client?">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="forceDelete({{ $client->id }})"
                                                wire:confirm="Are you sure you want to permanently delete this client? This cannot be undone!">
                                            <i class="bi bi-x-circle"></i> Delete Permanently
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $clients->links() }}
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No deleted clients.</p>
                </div>
            @endif
        </div>
    </div>
</div>