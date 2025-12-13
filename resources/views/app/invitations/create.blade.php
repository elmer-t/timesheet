@extends('layouts.app')

@section('title', 'Send Invitation')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h2">Send Team Invitation</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Invite a new user to join your organization. They will receive an email with instructions to join.</p>
                
                <form method="POST" action="{{ route('app.invitations.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> The invited user will be able to:
                        <ul class="mb-0 mt-2">
                            <li>View all clients and projects in your organization</li>
                            <li>Create and manage their own time registrations</li>
                            <li>Generate timesheets for their work</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Send Invitation</button>
                        <a href="{{ route('app.invitations.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
