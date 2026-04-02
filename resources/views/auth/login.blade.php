{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
/* ============================================
   LOGIN PAGE STYLES
   ============================================ */

/* Page Container */
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 16px;
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
    position: relative;
    overflow: hidden;
}

/* Background decoration */
.login-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--accent-muted) 0%, transparent 70%);
    opacity: 0.3;
    pointer-events: none;
    animation: rotate 60s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Card Wrapper */
.login-card {
    max-width: 440px;
    width: 100%;
    animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    z-index: 1;
}

/* Header Styles */
.login-header {
    text-align: center;
    margin-bottom: 32px;
}

.login-logo {
    font-size: 48px;
    margin-bottom: 16px;
    display: inline-block;
    animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.login-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.login-subtitle {
    font-size: 13px;
    color: var(--text-muted);
}

/* Form Container */
.login-form {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    transition: all var(--transition-normal) var(--ease-out);
}

.login-form:hover {
    border-color: var(--border-strong);
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
}

/* Form Groups */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-primary);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    font-size: 14px;
    color: var(--text-primary);
    transition: all var(--transition-fast) var(--ease-out);
}

.form-input:hover {
    border-color: var(--border-strong);
}

.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-muted);
}

.form-input.error {
    border-color: var(--danger);
}

.form-input.error:focus {
    box-shadow: 0 0 0 3px var(--danger-muted);
}

/* Input with icon */
.input-icon {
    position: relative;
}

.input-icon svg {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: var(--text-muted);
    pointer-events: none;
}

.input-icon .form-input {
    padding-left: 42px;
}

/* Password Toggle */
.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    transition: color var(--transition-fast);
}

.password-toggle:hover {
    color: var(--accent);
}

/* Remember Me & Forgot Password */
.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.remember-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.remember-checkbox input {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--accent);
}

.remember-checkbox label {
    font-size: 13px;
    color: var(--text-secondary);
    cursor: pointer;
}

.forgot-link {
    font-size: 13px;
    color: var(--accent);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.forgot-link:hover {
    color: var(--accent-light);
    text-decoration: underline;
}

/* Button Styles */
.login-button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-fast) var(--ease-out);
    position: relative;
    overflow: hidden;
}

.login-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.login-button:hover::before {
    width: 300px;
    height: 300px;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow);
}

.login-button:active {
    transform: translateY(0);
}

.login-button.loading {
    pointer-events: none;
    opacity: 0.7;
}

.login-button.loading::after {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    border: 2px solid white;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}

/* Footer Links */
.login-footer {
    text-align: center;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.login-footer p {
    font-size: 13px;
    color: var(--text-muted);
}

.login-footer a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    transition: color var(--transition-fast);
}

.login-footer a:hover {
    color: var(--accent-light);
    text-decoration: underline;
}

/* Alert Messages */
.alert {
    padding: 14px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    animation: slideUp 0.3s var(--ease-out);
}

.alert-success {
    background: var(--success-muted);
    border-left: 3px solid var(--success);
}

.alert-error {
    background: var(--danger-muted);
    border-left: 3px solid var(--danger);
}

.alert-warning {
    background: var(--warning-muted);
    border-left: 3px solid var(--warning);
}

.alert-info {
    background: var(--accent-muted);
    border-left: 3px solid var(--accent);
}

.alert-icon {
    font-size: 18px;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 2px;
    color: var(--text-primary);
}

.alert-message {
    font-size: 12px;
    color: var(--text-secondary);
}

/* Social Login */
.social-login {
    margin-top: 24px;
    text-align: center;
}

.social-divider {
    position: relative;
    text-align: center;
    margin: 20px 0;
}

.social-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--border);
}

.social-divider span {
    position: relative;
    background: var(--bg-primary);
    padding: 0 12px;
    font-size: 12px;
    color: var(--text-muted);
}

.social-buttons {
    display: flex;
    gap: 12px;
}

.social-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 12px;
    cursor: pointer;
    transition: all var(--transition-fast);
    color: var(--text-primary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.social-btn:hover {
    background: var(--bg-hover);
    border-color: var(--border-strong);
    transform: translateY(-1px);
}

.social-btn svg {
    width: 18px;
    height: 18px;
}

/* Demo Credentials */
.demo-credentials {
    margin-top: 20px;
    padding: 12px;
    background: var(--accent-muted);
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.demo-credentials:hover {
    background: var(--accent-muted);
    transform: translateY(-1px);
}

.demo-title {
    font-size: 11px;
    font-weight: 600;
    color: var(--accent);
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.demo-text {
    font-size: 11px;
    color: var(--text-muted);
    font-family: monospace;
}

/* Animations */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.shake {
    animation: shake 0.3s ease-in-out;
}

/* Responsive Design */
@media (max-width: 640px) {
    .login-container {
        padding: 24px 16px;
    }
    
    .login-form {
        padding: 24px;
    }
    
    .login-title {
        font-size: 24px;
    }
    
    .form-input {
        padding: 10px 14px;
    }
    
    .login-button {
        padding: 12px;
    }
    
    .social-buttons {
        flex-direction: column;
    }
}

/* Dark Theme Specific Adjustments */
.dark .login-form {
    background: var(--bg-primary);
}

.dark .social-btn {
    background: rgba(255, 255, 255, 0.03);
}

.dark .demo-credentials {
    background: rgba(96, 165, 250, 0.1);
}

/* Print Styles */
@media print {
    .login-container::before,
    .login-button::before,
    .social-login {
        display: none;
    }
    
    .login-form {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">🤖</div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your AI Agent SaaS account</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <div class="alert-title">Authentication Failed</div>
                    <div class="alert-message">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <div class="alert-icon">✓</div>
                <div class="alert-content">
                    <div class="alert-title">Success</div>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <div class="alert-icon">ℹ️</div>
                <div class="alert-content">
                    <div class="alert-title">Info</div>
                    <div class="alert-message">{{ session('info') }}</div>
                </div>
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-icon">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-input @error('email') error @enderror" 
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <div class="error-message" style="margin-top: 6px; font-size: 11px; color: var(--danger);">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <div class="input-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-input @error('password') error @enderror" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="toggleIcon" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="error-message" style="margin-top: 6px; font-size: 11px; color: var(--danger);">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-options">
                <label class="remember-checkbox">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me</label>
                </label>
                <a href="#" class="forgot-link" onclick="showForgotPassword()">Forgot password?</a>
            </div>

            <button type="submit" class="login-button" id="loginBtn">
                Sign In
            </button>

            <div class="login-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Create one here</a></p>
            </div>

            <!-- Demo Credentials (Optional) -->
            <div class="demo-credentials" onclick="fillDemoCredentials()">
                <div class="demo-title">🔑 Demo Credentials</div>
                <div class="demo-text">demo@example.com / password</div>
            </div>

            <!-- Social Login -->
            <div class="social-login">
                <div class="social-divider">
                    <span>Or continue with</span>
                </div>
                <div class="social-buttons">
                    <a href="#" class="social-btn" onclick="socialLogin('google')">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </a>
                    <a href="#" class="social-btn" onclick="socialLogin('github')">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12c0 4.42 2.87 8.17 6.84 9.49.5.09.68-.21.68-.48 0-.24-.01-.88-.01-1.73-2.78.6-3.37-1.34-3.37-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1.01.07 1.54 1.03 1.54 1.03.89 1.52 2.34 1.08 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02.8-.22 1.65-.33 2.5-.33.85 0 1.7.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.68-4.57 4.93.36.31.68.92.68 1.85 0 1.34-.01 2.42-.01 2.75 0 .27.18.58.69.48C19.13 20.17 22 16.42 22 12c0-5.52-4.48-10-10-10z"/>
                        </svg>
                        GitHub
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Password visibility toggle
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/><path d="M3.5 3.5L20.5 20.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
    } else {
        passwordInput.type = 'password';
        toggleIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
    }
}

// Demo credentials fill
function fillDemoCredentials() {
    document.querySelector('input[name="email"]').value = 'demo@example.com';
    document.querySelector('input[name="password"]').value = 'password';
    showToast('Demo credentials filled!', 'success');
}

// Forgot password handler
function showForgotPassword() {
    showToast('Password reset link sent to your email', 'info');
    // You can redirect to password reset page or show modal
    // window.location.href = '/password/reset';
}

// Social login handler
function socialLogin(provider) {
    showToast(`Connecting with ${provider}...`, 'info');
    // Redirect to social login route
    // window.location.href = `/auth/${provider}`;
}

// Form submission with loading state
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const loginBtn = document.getElementById('loginBtn');
    
    if (!email || !password) {
        e.preventDefault();
        showToast('Please fill in both email and password', 'error');
        return;
    }
    
    loginBtn.classList.add('loading');
    loginBtn.textContent = 'Signing in...';
});

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'var(--success)' : 
                    type === 'error' ? 'var(--danger)' : 
                    type === 'warning' ? 'var(--warning)' : 'var(--accent)';
    
    toast.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        padding: 12px 20px;
        background: ${bgColor};
        color: white;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        z-index: 10000;
        animation: slideUp 0.3s ease-out;
        box-shadow: var(--shadow-md);
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Enter key submission
document.getElementById('password').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('loginForm').submit();
    }
});

// Auto-focus on email field
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    }
});

// Remember me checkbox styling
const rememberCheckbox = document.getElementById('remember');
if (rememberCheckbox && localStorage.getItem('rememberEmail')) {
    rememberCheckbox.checked = true;
    const savedEmail = localStorage.getItem('savedEmail');
    if (savedEmail) {
        document.querySelector('input[name="email"]').value = savedEmail;
    }
}

// Save email if remember me is checked
rememberCheckbox?.addEventListener('change', function(e) {
    if (e.target.checked) {
        const email = document.querySelector('input[name="email"]').value;
        localStorage.setItem('savedEmail', email);
        localStorage.setItem('rememberEmail', 'true');
    } else {
        localStorage.removeItem('savedEmail');
        localStorage.removeItem('rememberEmail');
    }
});
</script>
@endsection