<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - TimeSheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 56px 0 0;
            background-color: #212529;
            width: 250px;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 56px;
        }
        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }
        @media print {
            .sidebar, .navbar, .no-print {
                display: none !important;
            }
            .main-content {
                margin-left: 0;
                padding-top: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('app.dashboard') }}">
                <i class="bi bi-clock-history"></i> TimeSheet
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="bi bi-building"></i> {{ auth()->user()->tenant->name }}
                </span>
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.dashboard') ? 'active' : '' }}" 
                   href="{{ route('app.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.registrations.*') ? 'active' : '' }}" 
                   href="{{ route('app.registrations.index') }}">
                    <i class="bi bi-clock"></i> Time Registrations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.timesheets.*') ? 'active' : '' }}" 
                   href="{{ route('app.timesheets.index') }}">
                    <i class="bi bi-file-earmark-text"></i> Timesheets
                </a>
            </li>
            
            @if(auth()->user()->isTenantAdmin())
                <li class="nav-item mt-3">
                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase">
                        <small>Administration</small>
                    </h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.clients.*') ? 'active' : '' }}" 
                       href="{{ route('app.clients.index') }}">
                        <i class="bi bi-people"></i> Clients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.projects.*') ? 'active' : '' }}" 
                       href="{{ route('app.projects.index') }}">
                        <i class="bi bi-briefcase"></i> Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.invitations.*') ? 'active' : '' }}" 
                       href="{{ route('app.invitations.index') }}">
                        <i class="bi bi-envelope"></i> Invitations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.settings.*') ? 'active' : '' }}" 
                       href="{{ route('app.settings.edit') }}">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
