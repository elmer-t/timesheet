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
            $dailyHours[$date] = ['hours' => 0, 'non_paid_hours' => 0, 'registrations' => []];
        }
        $dailyHours[$date]['hours'] += $registration->hours;
        
        // Track non-paid hours separately
        if ($registration->status === 'non_paid') {
            $dailyHours[$date]['non_paid_hours'] += $registration->hours;
        }
        
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
                                    $nonPaidHours = $dailyHours[$dateKey]['non_paid_hours'] ?? 0;
                                    $hasRegistrations = isset($dailyHours[$dateKey]);
                                    $isToday = $cellDate->isSameDay($today);
                                    
                                    // If all hours are non-paid, use grey background
                                    $bgClass = '';
                                    if ($hours > 0 && $nonPaidHours === $hours) {
                                        $bgClass = 'bg-non-paid';
                                    } elseif ($hours >= 8) {
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
                                                'project' => $r->project ? $r->project->name : null,
                                                'description' => $r->description,
                                                'hours' => $r->hours,
                                                'status' => $r->status,
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
                                            <small class="{{ $nonPaidHours === $hours ? 'text-muted' : 'text-dark' }}">{{ number_format($hours, 1) }}h</small>
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
        <small><span class="badge bg-non-paid text-muted">■</span> Non-paid hours</small>
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
                <button type="button" class="btn btn-primary" id="addNewFromDay">
                    <i class="bi bi-plus-circle"></i> Add New Registration
                </button>
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
                        <label for="client_id" class="form-label">Client *</label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">Select a client</option>
                            @foreach(auth()->user()->tenant->clients()->whereNull('deleted_at')->get() as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project</label>
                        <select name="project_id" id="project_id" class="form-select">
                            <option value="">Select a project (optional)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (hours) *</label>
                        <input type="number" name="duration" id="duration" class="form-control" step="0.25" min="0.25" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach(\App\Models\TimeRegistration::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ $value === 'ready_to_invoice' ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="modalErrors" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitRegistration">Create Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for editing registration -->
<div class="modal fade" id="editRegistrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editRegistrationForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editRegistrationId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Time Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="date" id="editRegistrationDate">
                    
                    <div class="mb-3">
                        <label for="edit_client_id" class="form-label">Client *</label>
                        <select name="client_id" id="edit_client_id" class="form-select" required>
                            <option value="">Select a client</option>
                            @foreach(auth()->user()->tenant->clients()->whereNull('deleted_at')->get() as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_project_id" class="form-label">Project</label>
                        <select name="project_id" id="edit_project_id" class="form-select">
                            <option value="">Select a project (optional)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_duration" class="form-label">Duration (hours) *</label>
                        <input type="number" name="duration" id="edit_duration" class="form-control" step="0.25" min="0.25" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status *</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            @foreach(\App\Models\TimeRegistration::getStatuses() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="editModalErrors" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitEditRegistration">Update Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission with AJAX for better feedback
    const registrationForm = document.querySelector('#newRegistrationModal form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitRegistration');
            const modalErrors = document.getElementById('modalErrors');
            const formData = new FormData(this);
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
            modalErrors.classList.add('d-none');
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    // Success - show toast and reload the page
                    const data = await response.json();
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newRegistrationModal'));
                    modal.hide();
                    
                    // Show success toast
                    if (window.showToast) {
                        window.showToast('success', data.message || 'Time registration created successfully.');
                    }
                    
                    // Reload after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    // Handle validation errors
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                        let errorHtml = '<ul class="mb-0">';
                        
                        if (data.errors) {
                            Object.values(data.errors).forEach(errors => {
                                errors.forEach(error => {
                                    errorHtml += `<li>${error}</li>`;
                                });
                            });
                        } else if (data.message) {
                            errorHtml += `<li>${data.message}</li>`;
                        } else {
                            errorHtml += '<li>An error occurred. Please try again.</li>';
                        }
                        
                        errorHtml += '</ul>';
                        modalErrors.innerHTML = errorHtml;
                    } else {
                        // Non-JSON response (possibly HTML error page)
                        const text = await response.text();
                        console.error('Server error:', text);
                        modalErrors.innerHTML = `<p class="mb-0">Server error (${response.status}). Please try again or contact support.</p>`;
                    }
                    modalErrors.classList.remove('d-none');
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Create Registration';
                }
            } catch (error) {
                console.error('Error:', error);
                modalErrors.innerHTML = `<p class="mb-0">Network error: ${error.message}. Please check your connection and try again.</p>`;
                modalErrors.classList.remove('d-none');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Create Registration';
            }
        });
    }
    
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
                
                // Add status badge styling
                let statusBadge = '';
                let statusClass = 'secondary';
                if (reg.status === 'non_paid') {
                    statusBadge = '<span class="badge bg-secondary text-white ms-1">Non-paid</span>';
                } else if (reg.status === 'paid') {
                    statusBadge = '<span class="badge bg-success text-white ms-1">Paid</span>';
                } else if (reg.status === 'invoiced') {
                    statusBadge = '<span class="badge bg-info text-white ms-1">Invoiced</span>';
                } else if (reg.status === 'ready_to_invoice') {
                    statusBadge = '<span class="badge bg-warning text-dark ms-1">Ready</span>';
                }
                
                html += `
                    <div class="list-group-item ${reg.status === 'non_paid' ? 'opacity-75' : ''}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${reg.project || '<span class="text-muted">No project</span>'}${statusBadge}</h6>
                                <p class="mb-1 text-muted small">${reg.description || 'No description'}</p>
                            </div>
                            <div class="text-end">
                                <strong>${reg.hours}h</strong>
                                <br>
                                <div class="btn-group mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editRegistration('${reg.id}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRegistration('${reg.id}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            html += `<div class="mt-3"><strong>Total: ${totalHours.toFixed(1)} hours</strong></div>`;
            
            modalBody.innerHTML = html;
            
            // Store date for 'Add New' button
            dayModal.dataset.currentDate = date;
        });
    }
    
    // Handle 'Add New Registration' button in day modal
    const addNewFromDayBtn = document.getElementById('addNewFromDay');
    if (addNewFromDayBtn) {
        addNewFromDayBtn.addEventListener('click', function() {
            const dayModal = document.getElementById('dayModal');
            const date = dayModal.dataset.currentDate;
            
            // Close day modal
            const dayModalInstance = bootstrap.Modal.getInstance(dayModal);
            dayModalInstance.hide();
            
            // Open new registration modal
            const newRegModal = document.getElementById('newRegistrationModal');
            const modalTitle = newRegModal.querySelector('#newRegistrationModalTitle');
            const dateInput = newRegModal.querySelector('#newRegistrationDate');
            
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            modalTitle.textContent = 'New Time Registration - ' + formattedDate;
            dateInput.value = date;
            
            const modal = new bootstrap.Modal(newRegModal);
            modal.show();
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

    // Handle client selection to populate projects (for new registration)
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

    // Handle client selection to populate projects (for edit modal)
    const editClientSelect = document.getElementById('edit_client_id');
    const editProjectSelect = document.getElementById('edit_project_id');
    
    if (editClientSelect && editProjectSelect) {
        editClientSelect.addEventListener('change', async function() {
            const clientId = this.value;
            editProjectSelect.innerHTML = '<option value="">Select a project (optional)</option>';
            
            if (clientId) {
                try {
                    const response = await fetch(`/app/clients/${clientId}/projects`);
                    const projects = await response.json();
                    
                    projects.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        editProjectSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching projects:', error);
                }
            }
        });
    }

    // Handle edit form submission with AJAX
    const editRegistrationForm = document.querySelector('#editRegistrationForm');
    if (editRegistrationForm) {
        editRegistrationForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitEditRegistration');
            const modalErrors = document.getElementById('editModalErrors');
            const formData = new FormData(this);
            const registrationId = document.getElementById('editRegistrationId').value;
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
            modalErrors.classList.add('d-none');
            
            try {
                const response = await fetch(`/app/registrations/${registrationId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    // Success - show toast and reload the page
                    const data = await response.json();
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editRegistrationModal'));
                    modal.hide();
                    
                    // Show success toast
                    if (window.showToast) {
                        window.showToast('success', data.message || 'Time registration updated successfully.');
                    }
                    
                    // Reload after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    // Handle validation errors
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                        let errorHtml = '<ul class="mb-0">';
                        
                        if (data.errors) {
                            Object.values(data.errors).forEach(errors => {
                                errors.forEach(error => {
                                    errorHtml += `<li>${error}</li>`;
                                });
                            });
                        } else if (data.message) {
                            errorHtml += `<li>${data.message}</li>`;
                        } else {
                            errorHtml += '<li>An error occurred. Please try again.</li>';
                        }
                        
                        errorHtml += '</ul>';
                        modalErrors.innerHTML = errorHtml;
                    } else {
                        // Non-JSON response
                        const text = await response.text();
                        console.error('Server error:', text);
                        modalErrors.innerHTML = `<p class="mb-0">Server error (${response.status}). Please try again or contact support.</p>`;
                    }
                    modalErrors.classList.remove('d-none');
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Update Registration';
                }
            } catch (error) {
                console.error('Error:', error);
                modalErrors.innerHTML = `<p class="mb-0">Network error: ${error.message}. Please check your connection and try again.</p>`;
                modalErrors.classList.remove('d-none');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Update Registration';
            }
        });
    }
});
</script>

<script>
// Global function to delete a registration
window.deleteRegistration = async function(registrationId) {
    if (!confirm('Are you sure you want to delete this time registration?')) {
        return;
    }
    
    try {
        const response = await fetch(`/app/registrations/${registrationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            if (window.showToast) {
                window.showToast('success', 'Time registration deleted successfully.');
            }
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            const data = await response.json();
            if (window.showToast) {
                window.showToast('error', data.message || 'Failed to delete registration.');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showToast) {
            window.showToast('error', 'Failed to delete registration. Please try again.');
        }
    }
};

// Global function to edit a registration
window.editRegistration = async function(registrationId) {
    try {
        const response = await fetch(`/app/registrations/${registrationId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch registration data');
        }
        
        const registration = await response.json();
        
        // Populate the form
        document.getElementById('editRegistrationId').value = registration.id;
        document.getElementById('editRegistrationDate').value = registration.date;
        document.getElementById('edit_client_id').value = registration.client_id;
        document.getElementById('edit_duration').value = registration.duration;
        document.getElementById('edit_description').value = registration.description || '';
        document.getElementById('edit_status').value = registration.status;
        
        // Load projects for the selected client
        const projectSelect = document.getElementById('edit_project_id');
        projectSelect.innerHTML = '<option value="">Select a project (optional)</option>';
        
        if (registration.client_id) {
            const projectsResponse = await fetch(`/app/clients/${registration.client_id}/projects`);
            const projects = await projectsResponse.json();
            
            projects.forEach(project => {
                const option = document.createElement('option');
                option.value = project.id;
                option.textContent = project.name;
                projectSelect.appendChild(option);
            });
            
            // Select the current project if it exists
            if (registration.project_id) {
                projectSelect.value = registration.project_id;
            }
        }
        
        // Close day modal if open
        const dayModalEl = document.getElementById('dayModal');
        if (dayModalEl) {
            const dayModalInstance = bootstrap.Modal.getInstance(dayModalEl);
            if (dayModalInstance) {
                dayModalInstance.hide();
            }
        }
        
        // Show edit modal
        const editModal = new bootstrap.Modal(document.getElementById('editRegistrationModal'));
        editModal.show();
        
    } catch (error) {
        console.error('Error:', error);
        if (window.showToast) {
            window.showToast('error', 'Failed to load registration. Please try again.');
        }
    }
};
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

.bg-non-paid {
    background-color: #e9ecef !important;
    opacity: 0.7;
}
</style>
