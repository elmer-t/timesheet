<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Waitlist</h1>
            <p class="text-muted">Manage waitlist signups</p>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($waitlist->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Submitted</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waitlist as $entry)
                                <tr>
                                    <td>
                                        <strong>{{ $entry->name }}</strong>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $entry->email }}">{{ $entry->email }}</a>
                                    </td>
                                    <td>{{ $entry->created_at->format('M d, Y g:i A') }}</td>
                                    <td>
                                        <button 
                                            wire:click="confirmDelete({{ $entry->id }})" 
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete entry"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $waitlist->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-list-ul" style="font-size: 3rem;"></i>
                    <p class="mt-3">No waitlist entries found.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this waitlist entry? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
