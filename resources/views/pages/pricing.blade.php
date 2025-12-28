@extends('layouts.page')

@section('title', 'Pricing')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Simple, Transparent Pricing</h1>
        <p class="lead text-muted">Choose the plan that works for you</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h3 class="card-title">Solo</h3>
                    <div class="display-4 fw-bold my-3">Free</div>
                    <p class="text-muted mb-4">Perfect for freelancers</p>
                    
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited time tracking</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 5 clients</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Up to 10 projects</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Basic reports</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Invoice generation</li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="btn btn-outline-primary mt-auto">Get Started</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-primary shadow h-100">
                <div class="card-header bg-primary text-white text-center">
                    <small>MOST POPULAR</small>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <h3 class="card-title">Team</h3>
                    <div class="display-4 fw-bold my-3">
                        $9<small class="fs-5 text-muted">/user/mo</small>
                    </div>
                    <p class="text-muted mb-4">For growing teams</p>
                    
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Everything in Solo</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited clients</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited projects</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Team collaboration</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Advanced analytics</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Priority support</li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="btn btn-primary mt-auto">Start Free Trial</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h3 class="card-title">Enterprise</h3>
                    <div class="display-4 fw-bold my-3">Custom</div>
                    <p class="text-muted mb-4">For large organizations</p>
                    
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Everything in Team</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Custom integrations</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Dedicated support</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>SLA guarantee</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Custom training</li>
                    </ul>
                    
                    <a href="{{ url('/pages/contact') }}" class="btn btn-outline-primary mt-auto">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-muted">All plans include 30-day free trial. No credit card required.</p>
    </div>
</div>
@endsection
