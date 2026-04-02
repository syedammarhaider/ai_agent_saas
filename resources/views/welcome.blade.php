{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
/* ============================================
   WELCOME/LANDING PAGE STYLES
   ============================================ */

.welcome-container {
    min-height: 100vh;
    padding: 48px 24px;
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
    position: relative;
    overflow: hidden;
}

/* Animated Background */
.welcome-container::before {
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

.welcome-container::after {
    content: '';
    position: absolute;
    bottom: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--success-muted) 0%, transparent 70%);
    opacity: 0.2;
    pointer-events: none;
    animation: rotateReverse 80s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes rotateReverse {
    from { transform: rotate(0deg); }
    to { transform: rotate(-360deg); }
}

.welcome-content {
    max-width: 1280px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

/* Hero Section */
.hero-section {
    text-align: center;
    margin-bottom: 80px;
    animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--accent-muted);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 500;
    color: var(--accent);
    margin-bottom: 24px;
}

.badge-dot {
    width: 8px;
    height: 8px;
    background: var(--accent);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.2);
    }
}

.hero-title {
    font-size: 56px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 36px;
    }
}

.gradient-text {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.hero-description {
    font-size: 18px;
    color: var(--text-muted);
    max-width: 600px;
    margin: 0 auto 32px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .hero-description {
        font-size: 16px;
    }
}

.button-group {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-large {
    padding: 14px 32px;
    font-size: 16px;
    font-weight: 600;
}

/* Features Grid */
.features-section {
    margin-bottom: 80px;
}

.section-title {
    text-align: center;
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--text-primary);
}

.section-subtitle {
    text-align: center;
    font-size: 16px;
    color: var(--text-muted);
    margin-bottom: 48px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.feature-card {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s var(--ease-out);
}

.feature-card:hover {
    transform: translateY(-8px);
    border-color: var(--border-strong);
    box-shadow: var(--shadow-lg);
}

.feature-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    background: var(--accent-muted);
}

.feature-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--text-primary);
}

.feature-description {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.6;
}

/* Stats Section */
.stats-section {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: 32px;
    padding: 48px;
    margin-bottom: 80px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 32px;
    text-align: center;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
}

.stat-number {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: var(--text-muted);
}

/* CTA Section */
.cta-section {
    text-align: center;
    padding: 48px;
    background: linear-gradient(135deg, var(--accent-muted) 0%, transparent 100%);
    border-radius: 32px;
}

.cta-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--text-primary);
}

.cta-description {
    font-size: 16px;
    color: var(--text-muted);
    margin-bottom: 32px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}

.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
</style>

<div class="welcome-container">
    <div class="welcome-content">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="badge">
                <span class="badge-dot"></span>
                AI-Powered Customer Support
            </div>
            
            <h1 class="hero-title">
                <span class="gradient-text">Automate Your</span>
                <br>
                <span style="color: var(--text-primary);">Customer Support</span>
            </h1>
            
            <p class="hero-description">
                Transform your customer service with intelligent AI agents that handle conversations, 
                create tasks, and integrate seamlessly with your existing tools.
            </p>
            
            <div class="button-group">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-large">
                        🚀 Start Free Trial
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-large">
                        Sign In
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-large">
                        Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>

        <!-- Features Grid -->
        <div class="features-section">
            <h2 class="section-title">Everything You Need</h2>
            <p class="section-subtitle">Powerful features to automate and enhance your customer support</p>
            
            <div class="features-grid">
                <div class="feature-card fade-in delay-100">
                    <div class="feature-icon" style="background: var(--accent-muted);">
                        <svg width="32" height="32" fill="currentColor" style="color: var(--accent);" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Smart Conversations</h3>
                    <p class="feature-description">AI agents understand context, provide accurate responses, and learn from every interaction.</p>
                </div>

                <div class="feature-card fade-in delay-200">
                    <div class="feature-icon" style="background: var(--success-muted);">
                        <svg width="32" height="32" fill="currentColor" style="color: var(--success);" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Task Automation</h3>
                    <p class="feature-description">Automatically create and assign tasks based on customer conversations and priorities.</p>
                </div>

                <div class="feature-card fade-in delay-300">
                    <div class="feature-icon" style="background: var(--warning-muted);">
                        <svg width="32" height="32" fill="currentColor" style="color: var(--warning);" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Multi-Platform</h3>
                    <p class="feature-description">Connect with WhatsApp, Slack, Email, and more platforms from a single dashboard.</p>
                </div>

                <div class="feature-card fade-in delay-100">
                    <div class="feature-icon" style="background: var(--info-muted);">
                        <svg width="32" height="32" fill="currentColor" style="color: var(--info);" viewBox="0 0 24 24">
                            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">24/7 Availability</h3>
                    <p class="feature-description">Never miss a customer inquiry with round-the-clock AI-powered support.</p>
                </div>

                <div class="feature-card fade-in delay-200">
                    <div class="feature-icon" style="background: rgba(168, 85, 247, 0.1);">
                        <svg width="32" height="32" fill="currentColor" style="color: #a855f7;" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Analytics & Insights</h3>
                    <p class="feature-description">Track performance, monitor satisfaction, and get actionable insights from your data.</p>
                </div>

                <div class="feature-card fade-in delay-300">
                    <div class="feature-icon" style="background: rgba(239, 68, 68, 0.1);">
                        <svg width="32" height="32" fill="currentColor" style="color: var(--danger);" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Enterprise Security</h3>
                    <p class="feature-description">Bank-level encryption and compliance to keep your customer data safe and secure.</p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div>
                    <div class="stat-number" style="color: var(--accent);">10K+</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div>
                    <div class="stat-number" style="color: var(--success);">2M+</div>
                    <div class="stat-label">Conversations Handled</div>
                </div>
                <div>
                    <div class="stat-number" style="color: var(--warning);">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
                <div>
                    <div class="stat-number" style="color: var(--info);">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2 class="cta-title">Ready to Transform Your Customer Support?</h2>
            <p class="cta-description">
                Join thousands of businesses already using AI Agent SaaS to deliver exceptional customer experiences.
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-large">
                    🚀 Start Your Free Trial
                </a>
            @endguest
        </div>
    </div>
</div>

<script>
// Add scroll animation
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.feature-card, .stats-section, .cta-section').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endsection