{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NexusAI') }}</title>

    {{-- External CSS — path: public/css/nexusai.css --}}
    <link rel="stylesheet" href="{{ asset('css/nexusai.css') }}">
</head>
<body>
    @auth
        <div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            {{-- Animated particles inside sidebar --}}
            <div class="sb-particles" id="sbParticles"></div>

            <div class="sb-logo-area">
                <div class="sb-logo-icon">
                    <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="sb-logo-text">DS AI</div>
                    <div class="sb-logo-sub">Agent Platform</div>
                </div>
            </div>

            <nav class="sb-nav">
                <div class="sb-section">
                    <div class="sb-section-label">Overview</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
                <div class="sb-section">
                    <div class="sb-section-label">Workspace</div>
                    <a href="{{ route('agent') }}" class="nav-link {{ request()->routeIs('agent') ? 'active' : '' }}">
                        <svg class="nl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/>
                        </svg>
                        Testing with AI
                    </a>
                    <a href="{{ route('clients') }}" class="nav-link {{ request()->routeIs('clients') ? 'active' : '' }}">
                        <svg class="nl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Clients
                    </a>
                </div>
            </nav>

            <div class="sb-footer">
                <div class="sb-user-row">
                    <div class="sb-user-av">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                    <div class="sb-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                </div>
                <div class="theme-toggle-row">
                    <span class="theme-toggle-label">
                        <svg id="theme-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <path stroke-linecap="round" d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                        </svg>
                        Dark Mode
                    </span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="themeToggle" onchange="toggleTheme(this.checked)">
                        <div class="toggle-track"></div>
                        <div class="toggle-thumb"></div>
                    </label>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="submit" class="sb-logout">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="mob-topbar">
            <button class="mob-menu-btn" onclick="openSidebar()">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="mob-title">DS AI</span>
        </div>

        <main class="main-content">
            @yield('content')
        </main>

    @else
        <main>@yield('content')</main>
    @endauth

    <div id="toast-container"></div>

    <script>
    /* ── THEME ── */
    function applyTheme(dark) {
        document.getElementById('html-root').setAttribute('data-theme', dark ? 'dark' : 'light');
        const tog = document.getElementById('themeToggle');
        if (tog) tog.checked = dark;
        const icon = document.getElementById('theme-icon');
        if (icon) icon.innerHTML = dark
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>'
            : '<circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>';
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    }
    function toggleTheme(checked) { applyTheme(checked); }
    (function() {
        const saved = localStorage.getItem('theme');
        const dark  = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyTheme(dark);
    })();
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) applyTheme(e.matches);
    });

    /* ── SIDEBAR ── */
    function openSidebar() {
        document.getElementById('sidebar')?.classList.add('open');
        document.getElementById('sbOverlay')?.classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar')?.classList.remove('open');
        document.getElementById('sbOverlay')?.classList.remove('show');
    }

    /* ── SIDEBAR PARTICLES ── */
    (function() {
        const container = document.getElementById('sbParticles');
        if (!container) return;
        for (let i = 0; i < 8; i++) {
            const p = document.createElement('div');
            p.className = 'sb-particle';
            const size = Math.random() * 3 + 1.5;
            p.style.cssText = `width:${size}px;height:${size}px;left:${Math.random()*100}%;`
                + `animation-duration:${Math.random()*12+14}s;`
                + `animation-delay:${Math.random()*8}s;`
                + `opacity:${Math.random()*.4+.1};`;
            container.appendChild(p);
        }
    })();

    /* ── LOGOUT CONFIRM ── */
    document.getElementById('logoutForm')?.addEventListener('submit', e => {
        if (!confirm('Sign out of NexusAI?')) e.preventDefault();
    });

    /* ── TOAST ── */
    window.showToast = function(msg, type = 'info') {
        const el = document.createElement('div');
        el.className = 'toast toast-' + type;
        el.textContent = msg;
        document.getElementById('toast-container').appendChild(el);
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(8px)';
            el.style.transition = '0.25s';
            setTimeout(() => el.remove(), 260);
        }, 3200);
    };
    </script>
</body>
</html>