<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Job Monitor</h1>
        </div>
        <div class="col-auto">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model.live="autoRefresh" id="autoRefresh">
                <label class="form-check-label" for="autoRefresh">
                    Auto-refresh (10s)
                </label>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Runs</div>
                    <div class="h3 mb-0">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-success bg-opacity-10" style="cursor: pointer;" wire:click="setFilter('success')">
                <div class="card-body">
                    <div class="text-success small mb-1">Successful</div>
                    <div class="h3 mb-0 text-success">{{ $stats['success'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-danger bg-opacity-10" style="cursor: pointer;" wire:click="setFilter('failed')">
                <div class="card-body">
                    <div class="text-danger small mb-1">Failed</div>
                    <div class="h3 mb-0 text-danger">{{ $stats['failed'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10" style="cursor: pointer;" wire:click="setFilter('running')">
                <div class="card-body">
                    <div class="text-primary small mb-1">Running</div>
                    <div class="h3 mb-0 text-primary">{{ $stats['running'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-3">
        <div class="btn-group" role="group">
            <button wire:click="setFilter('all')" 
                    class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                All
            </button>
            <button wire:click="setFilter('success')" 
                    class="btn {{ $filter === 'success' ? 'btn-success' : 'btn-outline-secondary' }}">
                Success
            </button>
            <button wire:click="setFilter('failed')" 
                    class="btn {{ $filter === 'failed' ? 'btn-danger' : 'btn-outline-secondary' }}">
                Failed
            </button>
            <button wire:click="setFilter('running')" 
                    class="btn {{ $filter === 'running' ? 'btn-info' : 'btn-outline-secondary' }}">
                Running
            </button>
        </div>
    </div>

    <!-- Job Runs Table -->
    <div class="card">
        <div class="card-body">
            @if($jobRuns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Name</th>
                                <th>Status</th>
                                <th>Duration</th>
                                <th>Details</th>
                                <th>Run Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobRuns as $jobRun)
                                <tr>
                                    <td>
                                        <strong>{{ str_replace('_', ' ', ucwords($jobRun->job_name, '_')) }}</strong>
                                    </td>
                                    <td>
                                        @if($jobRun->status === 'success')
                                            <span class="badge bg-success">Success</span>
                                        @elseif($jobRun->status === 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-primary">Running</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($jobRun->duration_seconds)
                                            {{ $jobRun->duration_seconds }}s
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($jobRun->error_message)
                                            <div class="text-danger small" style="max-width: 400px; overflow: hidden; text-overflow: ellipsis;" 
                                                 title="{{ $jobRun->error_message }}">
                                                {{ $jobRun->error_message }}
                                            </div>
                                        @elseif($jobRun->metadata)
                                            <div class="small text-muted">
                                                @if(isset($jobRun->metadata['filename']))
                                                    <div>File: {{ $jobRun->metadata['filename'] }}</div>
                                                @endif
                                                @if(isset($jobRun->metadata['file_size']))
                                                    <div>Size: {{ round($jobRun->metadata['file_size'] / 1024 / 1024, 2) }} MB</div>
                                                @endif
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $jobRun->created_at->format('Y-m-d H:i:s') }}</div>
                                        <div class="small text-muted">{{ $jobRun->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $jobRuns->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3">No job runs found.</p>
                    <p class="small">Run <code>php artisan backup:database</code> to create your first backup.</p>
                </div>
            @endif
        </div>
    </div>

    @if($autoRefresh)
        <script>
            setInterval(() => {
                @this.call('$refresh');
            }, 10000);
        </script>
    @endif
</div>
