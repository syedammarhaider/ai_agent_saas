{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
/* ============================================
   REGISTER PAGE STYLES
   ============================================ */

/* Page Container */
.register-container {
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
.register-container::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
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
.register-card {
    max-width: 440px;
    width: 100%;
    animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    z-index: 1;
}

/* Header Styles */
.register-header {
    text-align: center;
    margin-bottom: 32px;
}

.register-logo {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 12px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    letter-spacing: -0.5px;
}

.register-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.register-subtitle {
    font-size: 13px;
    color: var(--text-muted);
}

/* Form Container */
.register-form {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    transition: all var(--transition-normal) var(--ease-out);
}

.register-form:hover {
    border-color: var(--border-strong);
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
}

/* Form Groups */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.form-label span {
    color: var(--danger);
    margin-left: 2px;
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

/* Error Message */
.error-message {
    margin-top: 6px;
    font-size: 11px;
    color: var(--danger);
    display: flex;
    align-items: center;
    gap: 4px;
}

.error-message::before {
    content: '⚠️';
    font-size: 10px;
}

/* Password Strength Indicator */
.password-strength {
    margin-top: 8px;
}

.strength-bar {
    display: flex;
    gap: 4px;
    margin-bottom: 6px;
}

.strength-segment {
    flex: 1;
    height: 3px;
    background: var(--border);
    border-radius: 3px;
    transition: all var(--transition-fast) var(--ease-out);
}

.strength-segment.active {
    background: var(--success);
}

.strength-text {
    font-size: 10px;
    color: var(--text-muted);
}

/* Checkbox Group */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 0;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--accent);
}

.checkbox-group label {
    font-size: 13px;
    color: var(--text-secondary);
    cursor: pointer;
}

.checkbox-group a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
}

.checkbox-group a:hover {
    text-decoration: underline;
}

/* Button Styles */
.register-button {
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

.register-button::before {
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

.register-button:hover::before {
    width: 300px;
    height: 300px;
}

.register-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow);
}

.register-button:active {
    transform: translateY(0);
}

.register-button.loading {
    pointer-events: none;
    opacity: 0.7;
}

.register-button.loading::after {
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
.register-footer {
    text-align: center;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.register-footer p {
    font-size: 13px;
    color: var(--text-muted);
}

.register-footer a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    transition: color var(--transition-fast);
}

.register-footer a:hover {
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

/* Password Requirements */
.password-requirements {
    margin-top: 8px;
    padding: 8px 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    font-size: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.requirement {
    display: flex;
    align-items: center;
    gap: 4px;
    color: var(--text-muted);
    transition: color var(--transition-fast);
}

.requirement.met {
    color: var(--success);
}

.requirement i {
    font-size: 10px;
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
    .register-container {
        padding: 24px 16px;
    }
    
    .register-form {
        padding: 24px;
    }
    
    .register-title {
        font-size: 24px;
    }
    
    .form-input {
        padding: 10px 14px;
    }
    
    .register-button {
        padding: 12px;
    }
}

/* Loading Skeleton */
.skeleton {
    background: linear-gradient(90deg, var(--border) 25%, var(--border-strong) 50%, var(--border) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Tooltip */
.tooltip {
    position: relative;
    cursor: help;
}

.tooltip:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 6px 10px;
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 11px;
    color: var(--text-primary);
    white-space: nowrap;
    z-index: 10;
    box-shadow: var(--shadow-md);
    pointer-events: none;
}

/* Floating Labels */
.input-floating {
    position: relative;
}

.input-floating input {
    padding-top: 20px;
}

.input-floating label {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: var(--text-muted);
    transition: all var(--transition-fast) var(--ease-out);
    pointer-events: none;
}

.input-floating input:focus + label,
.input-floating input:not(:placeholder-shown) + label {
    top: 8px;
    font-size: 10px;
    color: var(--accent);
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
}

.social-btn:hover {
    background: var(--bg-hover);
    border-color: var(--border-strong);
    transform: translateY(-1px);
}

/* Dark Theme Specific Adjustments */
.dark .register-form {
    background: var(--bg-primary);
}

.dark .password-requirements {
    background: rgba(255, 255, 255, 0.03);
}

.dark .social-btn {
    background: rgba(255, 255, 255, 0.03);
}

/* Print Styles */
@media print {
    .register-container::before,
    .register-button::before,
    .social-login {
        display: none;
    }
    
    .register-form {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <div class="register-logo">🤖</div>
            <h1 class="register-title">Create Account</h1>
            <p class="register-subtitle">Join AI Agent SaaS and automate your customer support</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <div class="alert-title">Error</div>
                    <div class="alert-message">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <div class="alert-title">Please fix the following errors</div>
                    <div class="alert-message">
                        @foreach($errors->all() as $error)
                            • {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form class="register-form" method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Full Name <span>*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    class="form-input @error('name') error @enderror" 
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                    autofocus
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address <span>*</span></label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror" 
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password <span>*</span></label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="form-input @error('password') error @enderror" 
                    placeholder="Create a strong password"
                    required
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <!-- Password Strength Indicator -->
                <div class="password-strength" id="passwordStrength" style="display: none;">
                    <div class="strength-bar">
                        <div class="strength-segment" id="strength1"></div>
                        <div class="strength-segment" id="strength2"></div>
                        <div class="strength-segment" id="strength3"></div>
                        <div class="strength-segment" id="strength4"></div>
                    </div>
                    <div class="strength-text" id="strengthText">Weak</div>
                </div>

                <!-- Password Requirements -->
                <div class="password-requirements" id="passwordRequirements">
                    <div class="requirement" id="reqLength">
                        <i>✓</i> At least 8 characters
                    </div>
                    <div class="requirement" id="reqUpper">
                        <i>✓</i> Uppercase letter
                    </div>
                    <div class="requirement" id="reqNumber">
                        <i>✓</i> Number
                    </div>
                    <div class="requirement" id="reqSpecial">
                        <i>✓</i> Special character
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password <span>*</span></label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="passwordConfirmation"
                    class="form-input" 
                    placeholder="Confirm your password"
                    required
                >
                <div class="error-message" id="confirmError" style="display: none;">Passwords do not match</div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="terms" id="terms" required>
                <label for="terms">
                    I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                    <a href="#" target="_blank">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="register-button" id="registerBtn">
                Create Account
            </button>

            <div class="register-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>

            <!-- Social Login (Optional) -->
            <div class="social-login">
                <div class="social-divider">
                    <span>Or continue with</span>
                </div>
                <div class="social-buttons">
                    <a href="#" class="social-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </a>
                    <a href="#" class="social-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96v-7.01h-2.54v-2.89h2.54v-2.2c0-2.51 1.49-3.89 3.78-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.45 2.89h-2.33v7.01C18.34 21.21 22 17.06 22 12.06c0-5.53-4.5-10.02-10-10.02z"/>
                        </svg>
                        GitHub
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Password Strength Checker
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('passwordConfirmation');
    const strengthDiv = document.getElementById('passwordStrength');
    const confirmError = document.getElementById('confirmError');
    const registerBtn = document.getElementById('registerBtn');
    const form = document.getElementById('registerForm');
    
    // Password requirements elements
    const reqLength = document.getElementById('reqLength');
    const reqUpper = document.getElementById('reqUpper');
    const reqNumber = document.getElementById('reqNumber');
    const reqSpecial = document.getElementById('reqSpecial');
    
    // Strength segments
    const strength1 = document.getElementById('strength1');
    const strength2 = document.getElementById('strength2');
    const strength3 = document.getElementById('strength3');
    const strength4 = document.getElementById('strength4');
    const strengthText = document.getElementById('strengthText');
    
    function checkPasswordStrength(password) {
        let strength = 0;
        const checks = {
            length: password.length >= 8,
            upper: /[A-Z]/.test(password),
            lower: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        // Update requirements UI
        reqLength.classList.toggle('met', checks.length);
        reqUpper.classList.toggle('met', checks.upper);
        reqNumber.classList.toggle('met', checks.number);
        reqSpecial.classList.toggle('met', checks.special);
        
        // Calculate strength
        if (checks.length) strength++;
        if (checks.upper && checks.lower) strength++;
        if (checks.number) strength++;
        if (checks.special) strength++;
        
        // Update strength bars
        const segments = [strength1, strength2, strength3, strength4];
        segments.forEach((segment, i) => {
            if (i < strength) {
                segment.classList.add('active');
            } else {
                segment.classList.remove('active');
            }
        });
        
        // Update strength text
        const strengthLevels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const colors = ['var(--danger)', 'var(--danger)', 'var(--warning)', 'var(--accent)', 'var(--success)'];
        const level = Math.min(strength, 4);
        strengthText.textContent = strengthLevels[level];
        strengthText.style.color = colors[level];
        
        return strength;
    }
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        if (password.length > 0) {
            strengthDiv.style.display = 'block';
            checkPasswordStrength(password);
        } else {
            strengthDiv.style.display = 'none';
        }
        
        // Check password confirmation match
        if (confirmInput.value) {
            if (password !== confirmInput.value) {
                confirmError.style.display = 'block';
                confirmInput.classList.add('error');
            } else {
                confirmError.style.display = 'none';
                confirmInput.classList.remove('error');
            }
        }
    });
    
    confirmInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
            confirmError.style.display = 'block';
            this.classList.add('error');
        } else {
            confirmError.style.display = 'none';
            this.classList.remove('error');
        }
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        const terms = document.getElementById('terms').checked;
        let hasError = false;
        
        // Validate password strength
        if (password.length < 8) {
            e.preventDefault();
            showToast('Password must be at least 8 characters', 'error');
            hasError = true;
        }
        
        // Validate password match
        if (password !== confirm) {
            e.preventDefault();
            showToast('Passwords do not match', 'error');
            hasError = true;
        }
        
        // Validate terms
        if (!terms) {
            e.preventDefault();
            showToast('Please accept the Terms of Service', 'error');
            hasError = true;
        }
        
        if (!hasError) {
            registerBtn.classList.add('loading');
            registerBtn.textContent = 'Creating Account...';
        }
    });
    
    // Show toast function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 20px;
            background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--accent)'};
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
    
    // Auto-fill simulation for demo
    if (window.location.search.includes('demo=true')) {
        document.querySelector('input[name="name"]').value = 'John Doe';
        document.querySelector('input[name="email"]').value = 'john@example.com';
        passwordInput.value = 'Demo@123';
        confirmInput.value = 'Demo@123';
        checkPasswordStrength('Demo@123');
    }
});
</script>
@endsection