@extends('layouts.page')

@section('title', 'Features')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Powerful Features for Time Tracking</h1>
        <p class="lead text-muted">Everything you need to track time, manage projects, and get paid faster</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-stopwatch fs-1"></i>
                    </div>
                    <h5 class="card-title">Simple Time Tracking</h5>
                    <p class="card-text text-muted">Track time with just a few clicks. Start, stop, and log your hours effortlessly.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-folder fs-1"></i>
                    </div>
                    <h5 class="card-title">Project Management</h5>
                    <p class="card-text text-muted">Organize work by clients and projects. Set hourly rates and track profitability.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-file-earmark-text fs-1"></i>
                    </div>
                    <h5 class="card-title">Professional Invoicing</h5>
                    <p class="card-text text-muted">Generate invoices from time entries with flexible status tracking.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-bar-chart fs-1"></i>
                    </div>
                    <h5 class="card-title">Analytics & Reports</h5>
                    <p class="card-text text-muted">Visualize your time data with charts and detailed reports.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    <h5 class="card-title">Team Collaboration</h5>
                    <p class="card-text text-muted">Multi-tenant architecture with role-based permissions for teams.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-primary mb-3">
                        <i class="bi bi-moon-stars fs-1"></i>
                    </div>
                    <h5 class="card-title">Dark Mode</h5>
                    <p class="card-text text-muted">Native dark mode support for comfortable tracking at any time.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
            Get Started Free
        </a>
    </div>
</div>
@endsection
