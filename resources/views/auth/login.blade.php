{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="login-container" id="loginContainer">
    <div class="cursor-glow" id="cursorGlow"></div>
    <div class="particles" id="particles"></div>

    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">🤖</div>
            <h1 class="login-title">Admin Login</h1>
            <p class="login-subtitle">DS Technologies — Client Management System</p>
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
                        required autofocus>
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
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
                            required>
                    </div>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="toggleIcon" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-options">
                <label class="remember-checkbox">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me</label>
                </label>
            </div>

            <button type="submit" class="login-button" id="loginBtn">Sign In</button>
        </form>
    </div>
</div>

<script>
/* ── Cursor glow ── */
const cursorGlow = document.getElementById('cursorGlow');
const loginContainer = document.getElementById('loginContainer');
loginContainer.addEventListener('mousemove', e => {
    cursorGlow.style.opacity = '1';
    cursorGlow.style.left = e.clientX + 'px';
    cursorGlow.style.top  = e.clientY + 'px';
});
loginContainer.addEventListener('mouseleave', () => { cursorGlow.style.opacity = '0'; });

/* ── Particles ── */
(function() {
    const c = document.getElementById('particles');
    for (let i = 0; i < 20; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const s = Math.random() * 4 + 2;
        p.style.cssText = `width:${s}px;height:${s}px;left:${Math.random()*100}%;`
            + `animation-duration:${Math.random()*10+15}s;`
            + `animation-delay:${Math.random()*5}s;`
            + `opacity:${Math.random()*.5+.3};`;
        c.appendChild(p);
    }
})();

/* ── Password toggle ── */
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/><line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
    }
}

/* ── Form submit loading ── */
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email    = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const btn      = document.getElementById('loginBtn');
    if (!email || !password) {
        e.preventDefault();
        window.showToast?.('Please fill in both fields', 'error');
        return;
    }
    btn.classList.add('loading');
    btn.textContent = 'Signing in…';
});

/* ── Remember email ── */
const rememberChk = document.getElementById('remember');
const savedEmail  = localStorage.getItem('savedEmail');
if (savedEmail && localStorage.getItem('rememberEmail')) {
    document.querySelector('input[name="email"]').value = savedEmail;
    rememberChk.checked = true;
}
document.getElementById('loginForm').addEventListener('submit', function() {
    if (rememberChk.checked) {
        localStorage.setItem('savedEmail', document.querySelector('input[name="email"]').value);
        localStorage.setItem('rememberEmail', 'true');
    } else {
        localStorage.removeItem('savedEmail');
        localStorage.removeItem('rememberEmail');
    }
});

/* ── Input scale on focus ── */
document.querySelectorAll('.form-input').forEach(inp => {
    inp.addEventListener('focus', () => { inp.parentElement.style.transform = 'scale(1.01)'; });
    inp.addEventListener('blur',  () => { inp.parentElement.style.transform = 'scale(1)'; });
});
</script>
@endsection