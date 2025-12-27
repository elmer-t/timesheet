<div>
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">Tenants</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($tenants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Created</th>
                                <th>Users</th>
                                <th>Clients</th>
                                <th>Projects</th>
                                <th>Currency</th>
                                <th>Distance Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenants as $tenant)
                                <tr>
                                    <td>
                                        <strong>{{ $tenant->name }}</strong>
                                    </td>
                                    <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $tenant->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $tenant->clients_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $tenant->projects_count }}</span>
                                    </td>
                                    <td>{{ $tenant->defaultCurrency ? $tenant->defaultCurrency->code : 'N/A' }}</td>
                                    <td>{{ $tenant->distance_unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tenants->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-building" style="font-size: 3rem;"></i>
                    <p class="mt-3">No tenants found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
