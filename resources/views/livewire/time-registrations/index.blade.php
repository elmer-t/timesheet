<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Time Registrations</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('app.registrations.create') }}" class="btn btn-primary" wire:navigate>
                <i class="bi bi-plus-circle"></i> New Registration
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($registrations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Project</th>
                                <th>Duration</th>
                                <th>Revenue</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->date->format('M d, Y') }}</td>
                                    <td>{{ $registration->client->name }}</td>
                                    <td>
                                        @if($registration->project)
                                            {{ $registration->project->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($registration->duration, 2) }}h</td>
                                    <td>
                                        @if($registration->project)
                                            {{ $registration->project->currency->code }} {{ number_format($registration->revenue, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'ready_to_invoice' => 'bg-warning',
                                                'invoiced' => 'bg-info',
                                                'paid' => 'bg-success',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$registration->status] ?? 'bg-secondary' }}">
                                            {{ \App\Models\TimeRegistration::getStatuses()[$registration->status] ?? $registration->status }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($registration->description, 50) }}</td>
                                    <td>
                                        <a href="{{ route('app.registrations.edit', $registration) }}" 
                                           class="btn btn-sm btn-outline-primary" wire:navigate>
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteRegistration('{{ $registration->id }}')"
                                                wire:confirm="Are you sure you want to delete this time registration?">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $registrations->links() }}
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No time registrations yet. Create your first one!</p>
                </div>
            @endif
        </div>
    </div>
</div>
