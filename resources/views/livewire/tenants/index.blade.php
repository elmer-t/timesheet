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
                                        @php
                                            $userUsage = $tenant->user_limit > 0 ? ($tenant->users_count / $tenant->user_limit) * 100 : 0;
                                            $userBadge = $userUsage >= 100 ? 'danger' : ($userUsage >= 80 ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $userBadge }}">{{ $tenant->users_count }}</span> / {{ $tenant->user_limit }}
                                    </td>
                                    <td>
                                        @php
                                            $clientUsage = $tenant->client_limit > 0 ? ($tenant->clients_count / $tenant->client_limit) * 100 : 0;
                                            $clientBadge = $clientUsage >= 100 ? 'danger' : ($clientUsage >= 80 ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $clientBadge }}">{{ $tenant->clients_count }}</span> / {{ $tenant->client_limit }}
                                    </td>
                                    <td>
                                        @php
                                            $projectUsage = $tenant->project_limit > 0 ? ($tenant->projects_count / $tenant->project_limit) * 100 : 0;
                                            $projectBadge = $projectUsage >= 100 ? 'danger' : ($projectUsage >= 80 ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $projectBadge }}">{{ $tenant->projects_count }}</span> / {{ $tenant->project_limit }}
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
