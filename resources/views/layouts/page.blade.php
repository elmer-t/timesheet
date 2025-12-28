<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TimeSheet') - Time Tracking Made Simple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        main {
            flex: 1;
        }
        
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        
        [data-bs-theme="dark"] footer {
            background-color: #212529;
            border-top-color: #495057;
        }
        
        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.5rem;
            background: none;
            border: none;
        }
        
        .theme-toggle:hover {
            opacity: 0.8;
        }
        
        @yield('styles')
    </style>
    @stack('head')
</head>
<body data-bs-theme="{{ session('theme', 'light') }}">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-clock-history"></i> TimeSheet
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/pages/features') }}">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/pages/pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/pages/about') }}">About</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('app.calendar') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary ms-2" href="{{ route('register') }}">Sign Up</a>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <button class="theme-toggle nav-link" onclick="toggleTheme()" title="Toggle theme">
                            <i class="bi bi-moon-fill" id="theme-icon-moon"></i>
                            <i class="bi bi-sun-fill" id="theme-icon-sun" style="display: none;"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">
                        <i class="bi bi-clock-history"></i> TimeSheet
                    </h5>
                    <p class="text-muted">Time tracking made simple for teams and freelancers.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Product</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/pages/features') }}" class="text-decoration-none">Features</a></li>
                        <li><a href="{{ url('/pages/pricing') }}" class="text-decoration-none">Pricing</a></li>
                        <li><a href="{{ url('/pages/about') }}" class="text-decoration-none">About</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/pages/help') }}" class="text-decoration-none">Help Center</a></li>
                        <li><a href="{{ url('/pages/contact') }}" class="text-decoration-none">Contact</a></li>
                        <li><a href="{{ url('/pages/privacy') }}" class="text-decoration-none">Privacy</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-center text-muted">
                    <small>&copy; {{ date('Y') }} TimeSheet. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            document.body.setAttribute('data-bs-theme', newTheme);
            
            // Update icons
            document.getElementById('theme-icon-moon').style.display = newTheme === 'dark' ? 'none' : 'inline';
            document.getElementById('theme-icon-sun').style.display = newTheme === 'dark' ? 'inline' : 'none';
            
            // Persist theme preference
            fetch('/api/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ theme: newTheme })
            }).catch(() => {
                // Fallback to localStorage if API fails
                localStorage.setItem('theme', newTheme);
            });
        }
        
        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const theme = document.body.getAttribute('data-bs-theme');
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.getElementById('theme-icon-moon').style.display = theme === 'dark' ? 'none' : 'inline';
            document.getElementById('theme-icon-sun').style.display = theme === 'dark' ? 'inline' : 'none';
        });
    </script>
    @stack('scripts')
</body>
</html>
