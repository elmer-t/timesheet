@extends('layouts.app')

@section('title', 'Deleted Clients')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Deleted Clients</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('app.clients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Clients
        </a>
    </div>
</div>

@if($clients->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No deleted clients found.
    </div>
@else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->email ?? '-' }}</td>
                                <td>{{ $client->phone ?? '-' }}</td>
                                <td>{{ $client->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('app.clients.restore', $client->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to restore this client?')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('app.clients.force-destroy', $client->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will permanently delete this client and cannot be undone!')">
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
        {{ $clients->links() }}
    </div>
@endif
@endsection
