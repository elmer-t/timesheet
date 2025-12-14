<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - TimeSheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            transition: margin-left 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Light Theme (default) */
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        /* Dark Theme */
        body.dark-theme {
            background-color: #1a1d20;
            color: #e9ecef;
        }
        body.dark-theme .card {
            background-color: #2b3035;
            color: #e9ecef;
            border-color: #495057;
        }
        body.dark-theme .card-header {
            background-color: #212529;
            border-color: #495057;
            color: #e9ecef;
        }
        body.dark-theme .card-body {
            background-color: #2b3035;
            color: #e9ecef;
        }
        body.dark-theme .card-title {
            color: #e9ecef;
        }
        body.dark-theme .form-label {
            color: #e9ecef;
        }
        body.dark-theme .form-control,
        body.dark-theme .form-select {
            background-color: #343a40;
            color: #e9ecef;
            border-color: #495057;
        }
        body.dark-theme .form-control:focus,
        body.dark-theme .form-select:focus {
            background-color: #343a40;
            color: #e9ecef;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        body.dark-theme .form-control::placeholder {
            color: #6c757d;
        }
        body.dark-theme textarea.form-control {
            background-color: #343a40;
            color: #e9ecef;
        }
        body.dark-theme .invalid-feedback {
            color: #ea868f;
        }
        body.dark-theme .form-control.is-invalid,
        body.dark-theme .form-select.is-invalid {
            border-color: #dc3545;
        }
        body.dark-theme .table {
            color: #e9ecef;
            --bs-table-bg: #2b3035;
            --bs-table-striped-bg: #343a40;
            --bs-table-hover-bg: #3d4349;
            --bs-table-color: #e9ecef;
        }
        body.dark-theme .table-bordered {
            border-color: #495057;
        }
        body.dark-theme .table-bordered td,
        body.dark-theme .table-bordered th {
            border-color: #495057;
            color: #e9ecef;
        }
        body.dark-theme .table thead th {
            background-color: #212529;
            border-color: #495057;
            color: #e9ecef;
        }
        body.dark-theme .table tbody td {
            color: #e9ecef;
        }
        body.dark-theme .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: #3d4349;
            --bs-table-hover-color: #e9ecef;
            color: #e9ecef;
        }
        body.dark-theme .table-hover > tbody > tr:hover > * {
            color: #e9ecef;
        }
        body.dark-theme .small,
        body.dark-theme small {
            color: #adb5bd;
        }
        body.dark-theme strong {
            color: #f8f9fa;
        }
        body.dark-theme .form-control,
        body.dark-theme .form-select {
            background-color: #2b3035;
            color: #e9ecef;
            border-color: #495057;
        }
        body.dark-theme .form-control:focus,
        body.dark-theme .form-select:focus {
            background-color: #2b3035;
            color: #e9ecef;
            border-color: #0d6efd;
        }
        body.dark-theme .modal-content {
            background-color: #2b3035;
            color: #e9ecef;
        }
        body.dark-theme .modal-header,
        body.dark-theme .modal-footer {
            border-color: #495057;
        }
        body.dark-theme .modal-title {
            color: #e9ecef;
        }
        body.dark-theme .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        body.dark-theme .text-muted {
            color: #adb5bd !important;
        }
        body.dark-theme .bg-light {
            background-color: #343a40 !important;
        }
        body.dark-theme .bg-success {
            background-color: #198754 !important;
        }
        body.dark-theme .bg-warning {
            background-color: #ffc107 !important;
        }
        body.dark-theme .bg-opacity-25 {
            opacity: 0.4 !important;
        }
        body.dark-theme .badge {
            color: #fff;
        }
        body.dark-theme .alert {
            background-color: #2b3035;
            border-color: #495057;
        }
        body.dark-theme .alert-info {
            background-color: #0d6efd;
            border-color: #084298;
            color: #fff;
        }
        body.dark-theme a {
            color: #6ea8fe;
        }
        body.dark-theme a:hover {
            color: #8bb9fe;
        }
        body.dark-theme .text-decoration-none {
            color: inherit;
        }
        body.dark-theme .text-decoration-none:hover {
            color: #6ea8fe;
        }
        body.dark-theme code {
            background-color: #343a40;
            color: #e685b5;
        }
        
        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.5rem;
            color: #fff;
            background: none;
            border: none;
        }
        .theme-toggle:hover {
            color: #0d6efd;
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
            transition: width 0.3s ease;
            overflow-x: hidden;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
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
            min-width: 20px;
            text-align: center;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        .sidebar .nav-link-text {
            transition: opacity 0.3s ease;
        }
        .sidebar.collapsed .nav-link-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .sidebar .sidebar-heading {
            transition: opacity 0.3s ease;
        }
        .sidebar.collapsed .sidebar-heading {
            opacity: 0;
            height: 0;
            overflow: hidden;
            margin: 0 !important;
            padding: 0 !important;
        }
        .sidebar-toggle-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .sidebar-toggle-btn:hover {
            background-color: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 56px;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 70px;
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
    @livewireStyles
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('app.calendar') }}">
                <i class="bi bi-clock-history"></i> TimeSheet
            </a>
            <div class="d-flex align-items-center">
                <button class="theme-toggle me-3" id="themeToggle" onclick="toggleTheme()" title="Toggle theme">
                    <i class="bi bi-moon-stars" id="themeIcon"></i>
                </button>
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
    <div class="sidebar" id="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.calendar') ? 'active' : '' }}" 
                   href="{{ route('app.calendar') }}">
                    <i class="bi bi-calendar3"></i>
                    <span class="nav-link-text">Calendar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.analytics') ? 'active' : '' }}" 
                   href="{{ route('app.analytics') }}">
                    <i class="bi bi-graph-up"></i>
                    <span class="nav-link-text">Analytics</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.registrations.*') ? 'active' : '' }}" 
                   href="{{ route('app.registrations.index') }}">
                    <i class="bi bi-clock"></i>
                    <span class="nav-link-text">Time Registrations</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app.timesheets.*') ? 'active' : '' }}" 
                   href="{{ route('app.timesheets.index') }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="nav-link-text">Timesheets</span>
                </a>
            </li>
            
            @if(auth()->user()->isTenantAdmin())
                <li class="nav-item mt-3">
                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-white text-uppercase">
                        <small>Administration</small>
                    </h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.clients.*') ? 'active' : '' }}" 
                       href="{{ route('app.clients.index') }}">
                        <i class="bi bi-people"></i>
                        <span class="nav-link-text">Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.projects.*') ? 'active' : '' }}" 
                       href="{{ route('app.projects.index') }}">
                        <i class="bi bi-briefcase"></i>
                        <span class="nav-link-text">Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.users.*') ? 'active' : '' }}" 
                       href="{{ route('app.users.index') }}">
                        <i class="bi bi-people-fill"></i>
                        <span class="nav-link-text">Team Members</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app.settings.*') ? 'active' : '' }}" 
                       href="{{ route('app.settings.edit') }}">
                        <i class="bi bi-gear"></i>
                        <span class="nav-link-text">Settings</span>
                    </a>
                </li>
            @endif
        </ul>
        
        <!-- Toggle Button inside Sidebar -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" onclick="toggleSidebar()">
            <i class="bi bi-chevron-left" id="toggleIcon"></i>
        </button>
    </div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
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

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>
                    <span id="successToastMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <span id="errorToastMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            
            body.classList.toggle('dark-theme');
            
            if (body.classList.contains('dark-theme')) {
                themeIcon.className = 'bi bi-sun';
                localStorage.setItem('theme', 'dark');
            } else {
                themeIcon.className = 'bi bi-moon-stars';
                localStorage.setItem('theme', 'light');
            }
        }
        
        // Apply saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            
            if (savedTheme === 'dark') {
                body.classList.add('dark-theme');
                themeIcon.className = 'bi bi-sun';
            }
        });
        
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleIcon = document.getElementById('toggleIcon');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Toggle icon
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.className = 'bi bi-chevron-right';
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                toggleIcon.className = 'bi bi-chevron-left';
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }
        
        // Restore sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const toggleIcon = document.getElementById('toggleIcon');
                
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleIcon.className = 'bi bi-chevron-right';
            }
        });

        // Global function to show toast notifications
        window.showToast = function(type, message) {
            const toastId = type === 'success' ? 'successToast' : 'errorToast';
            const messageId = type === 'success' ? 'successToastMessage' : 'errorToastMessage';
            
            const toastEl = document.getElementById(toastId);
            const messageEl = document.getElementById(messageId);
            
            messageEl.textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        };
    </script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
