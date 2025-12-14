<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Calendar</h1>
        </div>
    </div>

    {{-- Calendar --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <button wire:click="previousMonth" class="btn btn-sm btn-outline-primary">&laquo; Previous</button>
            <div class="d-flex gap-2 align-items-center">
                <select wire:model.live="month" class="form-select form-select-sm" style="width: auto;">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <input type="number" wire:model.live="year" class="form-control form-control-sm" style="width: 80px;" min="2000" max="2100">
                <button wire:click="goToToday" class="btn btn-sm btn-outline-secondary">Today</button>
            </div>
            <button wire:click="nextMonth" class="btn btn-sm btn-outline-primary">Next &raquo;</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 40px;">#</th>
                            <th class="text-center">Mon</th>
                            <th class="text-center">Tue</th>
                            <th class="text-center">Wed</th>
                            <th class="text-center">Thu</th>
                            <th class="text-center">Fri</th>
                            <th class="text-center">Sat</th>
                            <th class="text-center">Sun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeks as $week)
                            <tr>
                                <td class="text-center align-middle bg-light text-muted small" style="width: 40px;">
                                    {{ $week[0]['date']->weekOfYear }}
                                </td>
                                @foreach($week as $day)
                                    @php
                                        $bgColor = '';
                                        if ($day['hours'] >= 8) {
                                            $bgColor = 'bg-success bg-opacity-25';
                                        } elseif ($day['hours'] > 0) {
                                            $bgColor = 'bg-warning bg-opacity-25';
                                        }
                                        
                                        $textColor = $day['isCurrentMonth'] ? '' : 'text-muted';
                                        if ($day['isToday']) {
                                            $textColor .= ' fw-bold';
                                        }
                                        
                                        $borderStyle = $day['isToday'] ? 'border: 2px solid #6c757d;' : '';
                                    @endphp
                                    <td class="p-2 {{ $bgColor }} {{ $textColor }}" 
                                        style="cursor: pointer; height: 80px; vertical-align: top; {{ $borderStyle }}"
                                        wire:click="openDay('{{ $day['dateString'] }}')">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="small">{{ $day['day'] }}</span>
                                        </div>
                                        @if($day['hours'] > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @if($day['by_status']['ready_to_invoice'] > 0)
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                                        {{ number_format($day['by_status']['ready_to_invoice'], 2) }}h
                                                    </span>
                                                @endif
                                                @if($day['by_status']['invoiced'] > 0)
                                                    <span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                        {{ number_format($day['by_status']['invoiced'], 2) }}h
                                                    </span>
                                                @endif
                                                @if($day['by_status']['paid'] > 0)
                                                    <span class="badge bg-success" style="font-size: 0.7rem;">
                                                        {{ number_format($day['by_status']['paid'], 2) }}h
                                                    </span>
                                                @endif
                                                @if($day['distance'] > 0)
                                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                        {{ $day['distance'] }} {{ Auth::user()->tenant->distance_unit }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" wire:click="closeModal">
            <div class="modal-dialog modal-lg" wire:click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Time Registrations - {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Existing registrations --}}
                        @if(count($editingRegistrations) > 0)
                            <h6 class="mb-3">Existing Registrations</h6>
                            <div class="list-group mb-4">
                                @foreach($editingRegistrations as $reg)
                                    <div class="list-group-item py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="mb-0">
                                                    <strong>{{ number_format($reg['duration'], 2) }}h</strong> - 
                                                    {{ $reg['client']['name'] }}
                                                    @if($reg['project'])
                                                        <span class="text-muted">/ {{ $reg['project']['name'] }}</span>
                                                    @endif
                                                </div>
                                                @if($reg['description'])
                                                    <small class="text-muted">{{ $reg['description'] }}</small>
                                                @endif
                                                @if($reg['location'] || $reg['distance'])
                                                    <small class="text-muted">
                                                        @if($reg['location'])
                                                            <span class="me-2">📍 {{ $reg['location'] }}</span>
                                                        @endif
                                                        @if($reg['distance'])
                                                            <span>🚗 {{ $reg['distance'] }} {{ Auth::user()->tenant->distance_unit }}</span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button wire:click="editRegistration('{{ $reg['id'] }}')" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="deleteRegistration('{{ $reg['id'] }}')" 
                                                        wire:confirm="Are you sure you want to delete this registration?"
                                                        class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Add/Edit form --}}
                        <h6 class="mb-3">{{ $registration_id ? 'Edit Registration' : 'Add New Registration' }}</h6>
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Client *</label>
                                    <select wire:model.live="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Project</label>
                                    <select wire:model="project_id" class="form-select @error('project_id') is-invalid @enderror" 
                                            @if(!$client_id) disabled @endif>
                                        <option value="">No Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" wire:model="date" class="form-control @error('date') is-invalid @enderror">
                                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Duration (h) *</label>
                                    <input type="number" step="0.01" wire:model="duration" class="form-control @error('duration') is-invalid @enderror">
                                    @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status *</label>
                                    <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="ready_to_invoice">Ready to Invoice</option>
                                        <option value="invoiced">Invoiced</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="2"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" wire:model="location" class="form-control @error('location') is-invalid @enderror" placeholder="e.g., Office, Client Site">
                                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Distance ({{ Auth::user()->tenant->distance_unit }})</label>
                                    <input type="number" step="1" wire:model="distance" class="form-control @error('distance') is-invalid @enderror" placeholder="0">
                                    @error('distance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ $registration_id ? 'Update' : 'Save' }}
                                </button>
                                @if($registration_id)
                                    <button type="button" wire:click="reset(['registration_id', 'client_id', 'project_id', 'duration', 'description', 'status', 'location', 'distance']); status = 'ready_to_invoice'" class="btn btn-secondary">
                                        Cancel Edit
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
