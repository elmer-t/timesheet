@props(['month', 'year', 'registrations', 'showModal' => true])

@php
    $monthDate = \Carbon\Carbon::create($year, $month, 1);
    $daysInMonth = $monthDate->daysInMonth;
    $firstDayOfWeek = $monthDate->dayOfWeek; // 0 = Sunday, 6 = Saturday
    $today = \Carbon\Carbon::today();
    
    // Group registrations by date and calculate totals
    $dailyHours = [];
    foreach ($registrations as $registration) {
        $date = $registration->date->format('Y-m-d');
        if (!isset($dailyHours[$date])) {
            $dailyHours[$date] = ['hours' => 0, 'registrations' => []];
        }
        $dailyHours[$date]['hours'] += $registration->hours;
        $dailyHours[$date]['registrations'][] = $registration;
    }
@endphp

<div class="calendar-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ $monthDate->format('F Y') }}</h5>
        <div class="btn-group">
            <a href="?month={{ $monthDate->copy()->subMonth()->month }}&year={{ $monthDate->copy()->subMonth()->year }}" 
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-chevron-left"></i>
            </a>
            <a href="?month={{ now()->month }}&year={{ now()->year }}" 
               class="btn btn-sm btn-outline-secondary">
                Today
            </a>
            <a href="?month={{ $monthDate->copy()->addMonth()->month }}&year={{ $monthDate->copy()->addMonth()->year }}" 
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered calendar-table">
            <thead>
                <tr>
                    <th class="text-center">Sun</th>
                    <th class="text-center">Mon</th>
                    <th class="text-center">Tue</th>
                    <th class="text-center">Wed</th>
                    <th class="text-center">Thu</th>
                    <th class="text-center">Fri</th>
                    <th class="text-center">Sat</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentDay = 1;
                    $totalWeeks = ceil(($daysInMonth + $firstDayOfWeek) / 7);
                @endphp
                
                @for ($week = 0; $week < $totalWeeks; $week++)
                    <tr>
                        @for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                            @php
                                $cellDay = ($week * 7 + $dayOfWeek) - $firstDayOfWeek + 1;
                            @endphp
                            
                            @if ($cellDay < 1 || $cellDay > $daysInMonth)
                                <td class="calendar-day empty"></td>
                            @else
                                @php
                                    $cellDate = \Carbon\Carbon::create($year, $month, $cellDay);
                                    $dateKey = $cellDate->format('Y-m-d');
                                    $hours = $dailyHours[$dateKey]['hours'] ?? 0;
                                    $hasRegistrations = isset($dailyHours[$dateKey]);
                                    $isToday = $cellDate->isSameDay($today);
                                    
                                    $bgClass = '';
                                    if ($hours >= 8) {
                                        $bgClass = 'bg-success-subtle';
                                    } elseif ($hours > 0) {
                                        $bgClass = 'bg-warning-subtle';
                                    }
                                @endphp
                                
                                <td class="calendar-day {{ $bgClass }} {{ $isToday ? 'today' : '' }} {{ $hasRegistrations ? 'has-registrations' : 'empty-day' }}"
                                    @if($showModal)
                                        @if($hasRegistrations)
                                            data-bs-toggle="modal" 
                                            data-bs-target="#dayModal" 
                                            data-date="{{ $dateKey }}"
                                            data-registrations="{{ json_encode(collect($dailyHours[$dateKey]['registrations'])->map(fn($r) => [
                                                'id' => $r->id,
                                                'project' => $r->project->name,
                                                'description' => $r->description,
                                                'hours' => $r->hours,
                                                'edit_url' => route('app.registrations.edit', $r->id)
                                            ])) }}"
                                        @else
                                            data-bs-toggle="modal" 
                                            data-bs-target="#newRegistrationModal" 
                                            data-date="{{ $dateKey }}"
                                        @endif
                                        style="cursor: pointer;"
                                    @endif
                                >
                                    <div class="calendar-day-number">{{ $cellDay }}</div>
                                    @if($hours > 0)
                                        <div class="calendar-day-hours">
                                            <small class="text-muted">{{ number_format($hours, 1) }}h</small>
                                        </div>
                                    @endif
                                </td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex gap-3">
        <small><span class="badge bg-success-subtle text-dark">■</span> 8+ hours</small>
        <small><span class="badge bg-warning-subtle text-dark">■</span> < 8 hours</small>
        <small><span class="badge bg-light text-dark">■</span> No registrations</small>
    </div>
</div>

@if($showModal)
<!-- Modal for showing day registrations -->
<div class="modal fade" id="dayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dayModalTitle">Time Registrations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="dayModalBody">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for creating new registration -->
<div class="modal fade" id="newRegistrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('app.registrations.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newRegistrationModalTitle">New Time Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="date" id="newRegistrationDate">
                    
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client</label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">Select a client</option>
                            @foreach(auth()->user()->tenant->clients()->whereNull('deleted_at')->get() as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project</label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            <option value="">Select a project</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (hours)</label>
                        <input type="number" name="duration" id="duration" class="form-control" step="0.25" min="0.25" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle day registrations modal
    const dayModal = document.getElementById('dayModal');
    if (dayModal) {
        dayModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const date = button.getAttribute('data-date');
            const registrations = JSON.parse(button.getAttribute('data-registrations'));
            
            const modalTitle = dayModal.querySelector('#dayModalTitle');
            const modalBody = dayModal.querySelector('#dayModalBody');
            
            // Format date
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            modalTitle.textContent = formattedDate;
            
            // Build registrations list
            let html = '<div class="list-group">';
            let totalHours = 0;
            
            registrations.forEach(reg => {
                totalHours += parseFloat(reg.hours);
                html += `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${reg.project}</h6>
                                <p class="mb-1 text-muted small">${reg.description || 'No description'}</p>
                            </div>
                            <div class="text-end">
                                <strong>${reg.hours}h</strong>
                                <br>
                                <a href="${reg.edit_url}" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            html += `<div class="mt-3"><strong>Total: ${totalHours.toFixed(1)} hours</strong></div>`;
            
            modalBody.innerHTML = html;
        });
    }

    // Handle new registration modal
    const newRegistrationModal = document.getElementById('newRegistrationModal');
    if (newRegistrationModal) {
        newRegistrationModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const date = button.getAttribute('data-date');
            
            const modalTitle = newRegistrationModal.querySelector('#newRegistrationModalTitle');
            const dateInput = newRegistrationModal.querySelector('#newRegistrationDate');
            
            // Format date
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            modalTitle.textContent = 'New Time Registration - ' + formattedDate;
            dateInput.value = date;
        });
    }

    // Handle client selection to populate projects
    const clientSelect = document.getElementById('client_id');
    const projectSelect = document.getElementById('project_id');
    
    if (clientSelect && projectSelect) {
        clientSelect.addEventListener('change', async function() {
            const clientId = this.value;
            projectSelect.innerHTML = '<option value="">Select a project</option>';
            
            if (clientId) {
                try {
                    const response = await fetch(`/app/clients/${clientId}/projects`);
                    const projects = await response.json();
                    
                    projects.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        projectSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching projects:', error);
                }
            }
        });
    }
});
</script>
@endif

<style>
.calendar-table {
    margin-bottom: 0;
}

.calendar-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 0.5rem;
}

.calendar-day {
    height: 80px;
    vertical-align: top;
    padding: 0.5rem;
    position: relative;
}

.calendar-day.empty {
    background-color: #f8f9fa;
}

.calendar-day.today {
    border: 2px solid #0d6efd;
}

.calendar-day.has-registrations:hover {
    opacity: 0.8;
}

.calendar-day.empty-day:hover {
    background-color: #e9ecef;
    opacity: 0.9;
}

.calendar-day-number {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.calendar-day-hours {
    position: absolute;
    bottom: 0.25rem;
    right: 0.5rem;
}

.bg-success-subtle {
    background-color: #d1e7dd !important;
}

.bg-warning-subtle {
    background-color: #fff3cd !important;
}
</style>
