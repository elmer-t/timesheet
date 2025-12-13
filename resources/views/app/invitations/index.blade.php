@extends('layouts.app')

@section('title', 'Invitations')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Team Invitations</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('app.invitations.create') }}" class="btn btn-primary">
            <i class="bi bi-envelope"></i> Send Invitation
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($invitations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Invited By</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invitations as $invitation)
                            <tr>
                                <td>{{ $invitation->email }}</td>
                                <td>{{ $invitation->invitedBy->name }}</td>
                                <td>{{ $invitation->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($invitation->isAccepted())
                                        <span class="badge bg-success">Accepted</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$invitation->isAccepted())
                                        <form method="POST" action="{{ route('app.invitations.destroy', $invitation) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Revoke
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $invitations->links() }}
        @else
            <div class="text-center py-5">
                <i class="bi bi-envelope text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No invitations sent yet. Invite team members to collaborate!</p>
            </div>
        @endif
    </div>
</div>
@endsection
