<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Asan Home Service</title>
    <meta name="description" content="Sign in to Asan Home Service Management System">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-brand-icon">
                <i class="bi bi-house-heart-fill"></i>
            </div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-sub">Sign in to Asan Home Service</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autofocus
                    autocomplete="email"
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="position-relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" id="togglePassword" class="btn btn-sm position-absolute" style="right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);" aria-label="Show/hide password">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label text-sm" for="remember" style="font-size:.82rem;color:var(--text-secondary);">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg" id="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In
            </button>
        </form>

        <div class="text-center mt-4" style="font-size:.76rem;color:var(--text-muted);">
            &copy; {{ date('Y') }} Asan Home Service. All rights reserved.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash-fill';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye-fill';
    }
});
</script>
</body>
</html>
