<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeSheet - Coming Soon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #f97316;
            --primary-hover: #ea580c;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 2rem 1rem;
        }
        
        .waitlist-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            border: 1px solid #e5e7eb;
        }
        
        .logo {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e5e7eb;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.15);
        }
        
        .btn-waitlist {
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-waitlist:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(249, 115, 22, 0.3);
            color: white;
        }
        
        .btn-waitlist:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
        }
        
        .alert-success {
            background-color: #d1f2eb;
            color: #0c5d47;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
        }
        
        .features-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0 0 0;
        }
        
        .features-list li {
            padding: 0.5rem 0;
            color: #6b7280;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        
        .features-list li i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .footer-text {
            margin-top: 1.5rem;
            color: #9ca3af;
            font-size: 0.875rem;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .waitlist-card {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="waitlist-card">
        <div class="text-center">
            <div class="logo">
                <i class="bi bi-clock-history"></i>
            </div>
            <h1>TimeSheet</h1>
            <p class="subtitle">
                Coming soon...
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form action="{{ route('waitlist.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    placeholder="Your name"
                    value="{{ old('name') }}"
                    required
                >
            </div>
            <div class="mb-4">
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    placeholder="Your email address"
                    value="{{ old('email') }}"
                    required
                >
            </div>
            <button type="submit" class="btn btn-waitlist">
                <i class="bi bi-envelope-check me-2"></i>
                Join the Waitlist
            </button>
        </form>

        <p class="footer-text">
            We respect your privacy. Your email will only be used to notify you about TimeSheet.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
