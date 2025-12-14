<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Team Members</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.users.create') }}" class="btn btn-primary" wire:navigate>
                <i class="bi bi-person-plus"></i> Add User
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('app.users.edit', $user) }}" class="text-decoration-none" wire:navigate>
                                            <strong>{{ $user->name }}</strong>
                                        </a>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-primary">You</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_admin)
                                            <span class="badge bg-success">Admin</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('app.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" wire:navigate>
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <button wire:click="sendOnboarding({{ $user->id }})"
                                                    class="btn btn-sm btn-outline-info" 
                                                    title="Send onboarding email">
                                                <i class="bi bi-envelope"></i>
                                            </button>
                                            
                                            @if($user->id !== auth()->id())
                                                <button wire:click="deleteUser({{ $user->id }})"
                                                        wire:confirm="Are you sure you want to delete this user?"
                                                        class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $users->links() }}
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No team members found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
