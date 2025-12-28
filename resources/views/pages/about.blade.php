@extends('layouts.page')

@section('title', 'About')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3">About TimeSheet</h1>
                <p class="lead text-muted">Simple, powerful time tracking for modern teams</p>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="mb-3">Our Mission</h3>
                    <p class="text-muted">
                        TimeSheet was built to solve a simple problem: time tracking shouldn't be complicated. 
                        We believe freelancers and teams deserve tools that are powerful yet intuitive, 
                        allowing them to focus on their work instead of wrestling with software.
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="mb-3">Built for Modern Teams</h3>
                    <p class="text-muted">
                        With multi-tenant architecture, role-based permissions, and real-time collaboration features, 
                        TimeSheet scales from solo freelancers to growing agencies. Track time, manage projects, 
                        and generate invoices—all in one place.
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="mb-3">Privacy First</h3>
                    <p class="text-muted mb-0">
                        Your data belongs to you. We use tenant isolation to ensure your information 
                        stays secure and private. No cross-tenant data leaks, no compromises.
                    </p>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 me-2">
                    Start Free Trial
                </a>
                <a href="{{ url('/pages/contact') }}" class="btn btn-outline-primary btn-lg px-5">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
