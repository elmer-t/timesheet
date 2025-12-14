<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Clients</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.clients.trashed') }}" class="btn btn-outline-secondary me-2" wire:navigate>
                <i class="bi bi-trash"></i> View Deleted
            </a>
            <a href="{{ route('app.clients.create') }}" class="btn btn-primary" wire:navigate>
                <i class="bi bi-plus-circle"></i> New Client
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td>
                                        <a href="{{ route('app.clients.edit', $client) }}" class="text-decoration-none" wire:navigate>
                                            <strong>{{ $client->name }}</strong>
                                        </a>
                                    </td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>
                                        <a href="{{ route('app.clients.edit', $client) }}" class="btn btn-sm btn-outline-primary" wire:navigate>
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteClient({{ $client->id }})"
                                                wire:confirm="Are you sure you want to delete this client?">
                                            <i class="bi bi-trash"></i>
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
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No clients yet. Create your first client to get started!</p>
                </div>
            @endif
        </div>
    </div>
</div>
