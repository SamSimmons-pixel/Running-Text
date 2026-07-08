<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login — {{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="Sign in to your account to continue.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            :root {
                --bg-dark: #0d0d14;
                --bg-card: rgba(255, 255, 255, 0.04);
                --border-color: rgba(255, 255, 255, 0.08);
                --border-focus: rgba(139, 92, 246, 0.6);
                --accent: #8b5cf6;
                --accent-light: #a78bfa;
                --accent-glow: rgba(139, 92, 246, 0.35);
                --text-primary: #f0eeff;
                --text-secondary: rgba(240, 238, 255, 0.5);
                --input-bg: rgba(255, 255, 255, 0.05);
                --error: #f87171;
            }

            html, body {
                height: 100%;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg-dark);
                color: var(--text-primary);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                position: relative;
            }

            /* Animated background blobs */
            .bg-blob {
                position: fixed;
                border-radius: 50%;
                filter: blur(100px);
                pointer-events: none;
                z-index: 0;
                animation: blobFloat 8s ease-in-out infinite;
            }

            .bg-blob-1 {
                width: 500px;
                height: 500px;
                background: radial-gradient(circle, rgba(139, 92, 246, 0.25) 0%, transparent 70%);
                top: -150px;
                left: -100px;
                animation-delay: 0s;
            }

            .bg-blob-2 {
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(59, 130, 246, 0.2) 0%, transparent 70%);
                bottom: -100px;
                right: -80px;
                animation-delay: -3s;
            }

            .bg-blob-3 {
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(236, 72, 153, 0.15) 0%, transparent 70%);
                top: 50%;
                left: 60%;
                animation-delay: -6s;
            }

            @keyframes blobFloat {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33%       { transform: translate(30px, -20px) scale(1.05); }
                66%       { transform: translate(-15px, 15px) scale(0.97); }
            }

            /* Grid pattern overlay */
            .bg-grid {
                position: fixed;
                inset: 0;
                z-index: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
                background-size: 48px 48px;
                pointer-events: none;
            }

            /* Card */
            .login-wrapper {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 420px;
                padding: 1.5rem;
                animation: cardAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            @keyframes cardAppear {
                from {
                    opacity: 0;
                    transform: translateY(24px) scale(0.97);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .login-card {
                background: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: 24px;
                padding: 2.5rem 2rem;
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                box-shadow:
                    0 0 0 1px rgba(255,255,255,0.05) inset,
                    0 32px 64px rgba(0,0,0,0.5),
                    0 0 60px var(--accent-glow);
                transition: box-shadow 0.3s ease;
            }

            /* Logo / Brand */
            .brand {
                text-align: center;
                margin-bottom: 2rem;
            }

            .brand-icon {
                width: 56px;
                height: 56px;
                background: linear-gradient(135deg, var(--accent) 0%, #3b82f6 100%);
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
                box-shadow: 0 8px 24px var(--accent-glow);
            }

            .brand-icon svg {
                width: 28px;
                height: 28px;
                color: white;
                fill: white;
            }

            .brand h1 {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-primary);
                letter-spacing: -0.02em;
                margin-bottom: 0.25rem;
            }

            .brand p {
                font-size: 0.875rem;
                color: var(--text-secondary);
                font-weight: 400;
            }

            /* Form */
            .form-group {
                margin-bottom: 1.25rem;
            }

            label {
                display: block;
                font-size: 0.8125rem;
                font-weight: 500;
                color: var(--text-secondary);
                margin-bottom: 0.5rem;
                letter-spacing: 0.01em;
                transition: color 0.2s;
            }

            .input-wrapper {
                position: relative;
            }

            .input-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-secondary);
                pointer-events: none;
                transition: color 0.2s;
            }

            .input-icon svg {
                width: 16px;
                height: 16px;
                display: block;
            }

            input[type="text"],
            input[type="password"] {
                width: 100%;
                background: var(--input-bg);
                border: 1px solid var(--border-color);
                border-radius: 12px;
                padding: 0.75rem 1rem 0.75rem 2.75rem;
                font-size: 0.9375rem;
                font-family: 'Inter', sans-serif;
                font-weight: 400;
                color: var(--text-primary);
                outline: none;
                transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
                -webkit-appearance: none;
            }

            input::placeholder {
                color: rgba(240, 238, 255, 0.25);
            }

            input:focus {
                border-color: var(--border-focus);
                background: rgba(139, 92, 246, 0.06);
                box-shadow: 0 0 0 3px var(--accent-glow), 0 0 0 1px var(--border-focus);
            }

            .form-group:focus-within label {
                color: var(--accent-light);
            }

            .form-group:focus-within .input-icon {
                color: var(--accent-light);
            }

            /* Password toggle */
            .password-toggle {
                position: absolute;
                right: 14px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: var(--text-secondary);
                padding: 0;
                display: flex;
                align-items: center;
                transition: color 0.2s;
            }

            .password-toggle:hover {
                color: var(--text-primary);
            }

            .password-toggle svg {
                width: 16px;
                height: 16px;
            }

            /* Error messages */
            .error-message {
                display: flex;
                align-items: center;
                gap: 0.375rem;
                margin-top: 0.5rem;
                font-size: 0.8125rem;
                color: var(--error);
                animation: errorSlide 0.2s ease;
            }

            @keyframes errorSlide {
                from { opacity: 0; transform: translateY(-4px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* Alert */
            .alert {
                border-radius: 12px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                margin-bottom: 1.25rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .alert-error {
                background: rgba(248, 113, 113, 0.1);
                border: 1px solid rgba(248, 113, 113, 0.25);
                color: var(--error);
            }

            .alert-success {
                background: rgba(52, 211, 153, 0.1);
                border: 1px solid rgba(52, 211, 153, 0.25);
                color: #6ee7b7;
            }

            /* Submit button */
            .btn-login {
                width: 100%;
                padding: 0.8125rem 1.5rem;
                margin-top: 0.5rem;
                background: linear-gradient(135deg, var(--accent) 0%, #6366f1 100%);
                border: none;
                border-radius: 12px;
                color: #fff;
                font-size: 0.9375rem;
                font-weight: 600;
                font-family: 'Inter', sans-serif;
                cursor: pointer;
                letter-spacing: 0.01em;
                position: relative;
                overflow: hidden;
                transition: transform 0.15s ease, box-shadow 0.2s ease, opacity 0.2s;
                box-shadow: 0 4px 20px rgba(139, 92, 246, 0.4);
            }

            .btn-login::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
                opacity: 0;
                transition: opacity 0.2s;
            }

            .btn-login:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 32px rgba(139, 92, 246, 0.55);
            }

            .btn-login:hover::before {
                opacity: 1;
            }

            .btn-login:active {
                transform: translateY(0);
                box-shadow: 0 2px 10px rgba(139, 92, 246, 0.35);
            }

            .btn-login:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            /* Spinner inside button */
            .btn-login .spinner {
                display: none;
                width: 18px;
                height: 18px;
                border: 2px solid rgba(255,255,255,0.3);
                border-top-color: white;
                border-radius: 50%;
                animation: spin 0.7s linear infinite;
                margin: 0 auto;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            .btn-login.loading .btn-text { display: none; }
            .btn-login.loading .spinner  { display: block; }

            /* Divider / footer */
            .card-footer {
                margin-top: 1.5rem;
                text-align: center;
                font-size: 0.8125rem;
                color: var(--text-secondary);
            }

            .card-footer a {
                color: var(--accent-light);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s;
            }

            .card-footer a:hover {
                color: var(--text-primary);
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

        <!-- Ambient background -->
        <div class="bg-blob bg-blob-1"></div>
        <div class="bg-blob bg-blob-2"></div>
        <div class="bg-blob bg-blob-3"></div>
        <div class="bg-grid"></div>

        <div class="login-wrapper">
            <div class="login-card">

                <!-- Brand -->
                <div class="brand">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2a10 10 0 1 1 0 20A10 10 0 0 1 12 2zm0 3a7 7 0 1 0 0 14A7 7 0 0 0 12 5zm0 2a5 5 0 1 1 0 10A5 5 0 0 1 12 7zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                        </svg>
                    </div>
                    <h1>Welcome back</h1>
                    <p>Sign in to your account to continue</p>
                </div>

                <!-- Session / Validation alerts -->
                @if (session('error'))
                    <div class="alert alert-error" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('login.post') }}" novalidate>
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Enter your name"
                                value="{{ old('name') }}"
                                autocomplete="username"
                                required
                            >
                        </div>
                        @error('name')
                            <div class="error-message">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group" style="display: flex; align-items: center; margin-bottom: 1.5rem; margin-top: -0.25rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; margin-bottom: 0;">
                            <input type="checkbox" name="remember" value="1" style="accent-color: var(--accent); width: 16px; height: 16px; border-radius: 4px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); cursor: pointer;">
                            <span style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 500;">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="btn-text">Sign in</span>
                        <span class="spinner"></span>
                    </button>
                </form>

                <div class="card-footer">
                    Don't have an account? <a href="#">Contact administrator</a>
                </div>

            </div>
        </div>

        <script>
            // Password toggle
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            const eyeOpen = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

            toggleBtn.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                eyeIcon.innerHTML = isHidden ? eyeClosed : eyeOpen;
            });

            // Loading state on submit
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');

            form.addEventListener('submit', (e) => {
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
            });
        </script>

    </body>
</html>
