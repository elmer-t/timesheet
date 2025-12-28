<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeSheet - Time Registration for Professionals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
        }
        .cta-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-clock-history text-primary"></i> TimeSheet
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="{{ route('register') }}">Get Started Free</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Simple Time Tracking for Professionals</h1>
                    <p class="lead mb-4">Track your time, manage clients and projects, and generate professional timesheets - all in one place.</p>
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Start Free Trial</a>
                </div>
                <div class="col-lg-6">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect fill='%23fff' opacity='0.1' width='400' height='300' rx='10'/%3E%3C/svg%3E" alt="Dashboard Preview" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Everything You Need</h2>
                <p class="lead text-muted">Powerful features designed for self-employed professionals</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="bi bi-clock feature-icon"></i>
                        <h4 class="mt-3">Easy Time Tracking</h4>
                        <p class="text-muted">Log your hours quickly with a simple, intuitive interface. Just date and duration - no complexity.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="bi bi-people feature-icon"></i>
                        <h4 class="mt-3">Team Collaboration</h4>
                        <p class="text-muted">Invite team members to collaborate on projects. Perfect for small teams and freelancers.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="bi bi-briefcase feature-icon"></i>
                        <h4 class="mt-3">Client & Project Management</h4>
                        <p class="text-muted">Organize work by clients and projects. Set hourly rates and track revenue automatically.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="bi bi-file-earmark-text feature-icon"></i>
                        <h4 class="mt-3">Professional Timesheets</h4>
                        <p class="text-muted">Generate and print timesheets by client, filtered by day, week, or month.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="bi bi-currency-dollar feature-icon"></i>
                        <h4 class="mt-3">Revenue Tracking</h4>
                        <p class="text-muted">Automatically calculate revenue based on project hourly rates and time logged.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h4 class="mt-3">Secure & Private</h4>
                        <p class="text-muted">Your data is protected and secure. Built with privacy and data protection in mind.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Simple, Transparent Pricing</h2>
                <p class="lead text-muted">Choose the plan that fits your needs</p>
            </div>
            <div class="row g-4">
                <!-- Free Tier -->
                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title fw-bold">Free</h3>
                            <div class="display-4 fw-bold mb-3">€0<small class="text-muted fs-6">/month</small></div>
                            <p class="text-muted mb-4">Perfect for getting started</p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 3 clients</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 3 projects</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited time tracking</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Basic reporting</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Get Started</a>
                        </div>
                    </div>
                </div>

                <!-- Standard Tier -->
                <div class="col-lg-4">
                    <div class="card h-100 shadow border-primary" style="border-width: 2px;">
                        <div class="card-header bg-primary text-white text-center py-3">
                            <span class="badge bg-white text-primary">MOST POPULAR</span>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="card-title fw-bold">Standard</h3>
                            <div class="display-4 fw-bold mb-3">€2.99<small class="text-muted fs-6">/month</small></div>
                            <p class="text-muted mb-4">For growing professionals</p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 10 clients</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 10 projects</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited time tracking</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Advanced reporting</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Multi-currency support</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-primary w-100">Start Free Trial</a>
                        </div>
                    </div>
                </div>

                <!-- Pro Tier -->
                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title fw-bold">Pro</h3>
                            <div class="display-4 fw-bold mb-3">€ 9.99</div>
                            <p class="text-muted mb-4">For teams and agencies</p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited clients</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited projects</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited time tracking</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Advanced reporting</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Multi-currency support</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Team collaboration</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Priority support</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
                    <p class="lead mb-4">Join thousands of professionals who trust TimeSheet for their time tracking needs.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Create Free Account</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} TimeSheet. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
