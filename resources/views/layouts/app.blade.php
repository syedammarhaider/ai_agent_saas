{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NexusAI') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== DESIGN TOKENS ===== */
        :root {
            --font-display: 'Syne', system-ui, sans-serif;
            --font-body: 'Figtree', system-ui, sans-serif;
            --font-mono: 'DM Mono', monospace;
            --ease: cubic-bezier(.22,1,.36,1);
            --ease-back: cubic-bezier(.34,1.56,.64,1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --radius-xl: 24px;
            --sidebar-w: 256px;
            --trans: 200ms var(--ease);
        }

        /* LIGHT THEME — Refined warm neutrals + electric indigo */
        [data-theme="light"] {
            --bg:           #F6F5F2;
            --bg-card:      #FFFFFF;
            --bg-raised:    #FAFAF8;
            --bg-hover:     #F0EEE9;
            --bg-input:     #FFFFFF;
            --border:       rgba(0,0,0,0.07);
            --border-md:    rgba(0,0,0,0.11);
            --border-str:   rgba(0,0,0,0.17);
            --txt:          #1C1917;
            --txt-2:        #44403C;
            --txt-3:        #78716C;
            --txt-4:        #A8A29E;
            --accent:       #4F46E5;
            --accent-2:     #7C3AED;
            --accent-soft:  rgba(79,70,229,0.08);
            --accent-glow:  rgba(79,70,229,0.18);
            --green:        #059669;
            --green-soft:   rgba(5,150,105,0.09);
            --red:          #DC2626;
            --red-soft:     rgba(220,38,38,0.08);
            --amber:        #D97706;
            --amber-soft:   rgba(217,119,6,0.09);
            --cyan:         #0891B2;
            --cyan-soft:    rgba(8,145,178,0.09);
            --purple:       #7C3AED;
            --purple-soft:  rgba(124,58,237,0.09);
            --shadow-sm:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:    0 4px 16px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.04);
            --shadow-lg:    0 10px 32px rgba(0,0,0,0.10), 0 4px 12px rgba(0,0,0,0.06);
            --sidebar-bg:   #FFFFFF;
            --sidebar-bdr:  rgba(0,0,0,0.07);
            --nav-active-bg: rgba(79,70,229,0.08);
        }

        /* DARK THEME — Deep slate + vivid indigo */
        [data-theme="dark"] {
            --bg:           #0C0D11;
            --bg-card:      #13151C;
            --bg-raised:    #191C27;
            --bg-hover:     #1E2230;
            --bg-input:     #191C27;
            --border:       rgba(255,255,255,0.07);
            --border-md:    rgba(255,255,255,0.11);
            --border-str:   rgba(255,255,255,0.17);
            --txt:          #F1F0EE;
            --txt-2:        #B4B0AC;
            --txt-3:        #6E6A66;
            --txt-4:        #46423E;
            --accent:       #6366F1;
            --accent-2:     #8B5CF6;
            --accent-soft:  rgba(99,102,241,0.12);
            --accent-glow:  rgba(99,102,241,0.22);
            --green:        #10B981;
            --green-soft:   rgba(16,185,129,0.11);
            --red:          #EF4444;
            --red-soft:     rgba(239,68,68,0.11);
            --amber:        #F59E0B;
            --amber-soft:   rgba(245,158,11,0.11);
            --cyan:         #06B6D4;
            --cyan-soft:    rgba(6,182,212,0.11);
            --purple:       #8B5CF6;
            --purple-soft:  rgba(139,92,246,0.11);
            --shadow-sm:    0 1px 3px rgba(0,0,0,0.30);
            --shadow-md:    0 4px 16px rgba(0,0,0,0.40);
            --shadow-lg:    0 10px 32px rgba(0,0,0,0.50);
            --sidebar-bg:   #10121A;
            --sidebar-bdr:  rgba(255,255,255,0.06);
            --nav-active-bg: rgba(99,102,241,0.12);
        }

        /* ===== RESET ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { color-scheme: light; }
        [data-theme="dark"] { color-scheme: dark; }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--txt);
            line-height: 1.55;
            min-height: 100vh;
            transition: background var(--trans), color var(--trans);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-bdr);
            display: flex;
            flex-direction: column;
            z-index: 900;
            transition: transform var(--trans), background var(--trans);
        }

        @media(max-width:1023px) {
            .sidebar { transform: translateX(-100%); box-shadow: var(--shadow-lg); }
            .sidebar.open { transform: translateX(0); }
        }

        .sb-logo-area {
            padding: 22px 18px 18px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 11px;
        }

        .sb-logo-icon {
            width: 36px; height: 36px; border-radius: 11px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 12px var(--accent-glow);
        }
        .sb-logo-icon svg { color: white; }

        .sb-logo-text {
            font-family: var(--font-display);
            font-weight: 800; font-size: 16px;
            color: var(--txt); letter-spacing: -0.4px;
        }
        .sb-logo-sub { font-size: 11px; color: var(--txt-4); margin-top: 1px; letter-spacing: 0.2px; }

        .sb-nav { flex: 1; padding: 14px 10px; overflow-y: auto; }

        .sb-section { margin-bottom: 24px; }

        .sb-section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: var(--txt-4);
            padding: 0 10px; margin-bottom: 6px; font-family: var(--font-mono);
        }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: var(--radius-md);
            font-size: 13.5px; font-weight: 500; color: var(--txt-3);
            text-decoration: none; transition: all var(--trans);
            margin-bottom: 2px; position: relative; font-family: var(--font-body);
        }
        .nav-link:hover { background: var(--bg-hover); color: var(--txt); }
        .nav-link.active {
            background: var(--nav-active-bg); color: var(--accent); font-weight: 600;
        }
        .nav-link.active .nl-icon { opacity: 1; color: var(--accent); }
        .nl-icon { width: 17px; height: 17px; flex-shrink: 0; opacity: 0.6; transition: opacity var(--trans); }
        .nav-link:hover .nl-icon { opacity: 1; }

        /* Active indicator bar */
        .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%; background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        /* ===== SIDEBAR FOOTER ===== */
        .sb-footer {
            padding: 14px 10px;
            border-top: 1px solid var(--border);
        }

        .theme-toggle-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 12px; border-radius: var(--radius-md);
        }
        .theme-toggle-label {
            font-size: 13px; color: var(--txt-3); display: flex; align-items: center; gap: 8px;
            font-weight: 500;
        }
        .toggle-switch { position: relative; width: 42px; height: 24px; cursor: pointer; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-track {
            position: absolute; inset: 0; border-radius: 12px;
            background: var(--border-md); transition: background var(--trans);
            border: 1px solid var(--border-str);
        }
        .toggle-switch input:checked + .toggle-track { background: var(--accent); border-color: var(--accent); }
        .toggle-thumb {
            position: absolute; top: 3px; left: 3px;
            width: 18px; height: 18px; border-radius: 50%;
            background: white; transition: transform var(--trans);
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        .toggle-switch input:checked ~ .toggle-thumb { transform: translateX(18px); }

        .sb-user-row {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: var(--radius-md);
            margin-bottom: 4px;
        }
        .sb-user-av {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: white; font-family: var(--font-display);
        }
        .sb-user-name { font-size: 13px; font-weight: 600; color: var(--txt); flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .sb-logout {
            display: flex; align-items: center; gap: 8px; width: 100%;
            padding: 9px 12px; border-radius: var(--radius-md);
            font-size: 13px; font-weight: 500; color: var(--red);
            background: none; border: none; cursor: pointer;
            transition: background var(--trans); font-family: var(--font-body);
            text-align: left;
        }
        .sb-logout:hover { background: var(--red-soft); }

        /* ===== MOBILE ===== */
        .mob-topbar {
            display: none;
            position: fixed; top: 0; left: 0; right: 0; z-index: 800;
            background: var(--bg-card); border-bottom: 1px solid var(--border);
            padding: 12px 16px; align-items: center; gap: 12px;
            backdrop-filter: blur(10px);
        }
        @media(max-width:1023px) { .mob-topbar { display: flex; } }

        .mob-menu-btn {
            width: 38px; height: 38px; border-radius: var(--radius-sm);
            border: 1px solid var(--border-md); background: var(--bg-card);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--txt-2); transition: all var(--trans);
        }
        .mob-menu-btn:hover { border-color: var(--accent); color: var(--accent); }

        .mob-title {
            font-size: 16px; font-weight: 800; color: var(--txt);
            font-family: var(--font-display); letter-spacing: -0.3px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin var(--trans);
        }
        @media(max-width:1023px) { .main-content { margin-left: 0; padding-top: 62px; } }

        /* ===== OVERLAY ===== */
        .sb-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); backdrop-filter: blur(3px); z-index: 850;
        }
        .sb-overlay.show { display: block; }

        /* ===== SHARED COMPONENTS ===== */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            padding: 9px 18px; border-radius: var(--radius-md);
            font-size: 13px; font-weight: 600; font-family: var(--font-body);
            cursor: pointer; border: none; transition: all var(--trans); text-decoration: none;
            letter-spacing: 0.1px;
        }
        .btn-primary {
            background: var(--accent); color: white;
            box-shadow: 0 2px 10px var(--accent-glow);
        }
        .btn-primary:hover { filter: brightness(1.09); transform: translateY(-1px); box-shadow: 0 6px 18px var(--accent-glow); }
        .btn-primary:active { transform: translateY(0); }

        .btn-ghost {
            background: transparent; color: var(--txt-2);
            border: 1px solid var(--border-md);
        }
        .btn-ghost:hover { background: var(--bg-hover); color: var(--txt); border-color: var(--border-str); }

        .btn-danger {
            background: var(--red-soft); color: var(--red);
            border: 1px solid rgba(220,38,38,0.2);
        }
        .btn-danger:hover { background: var(--red); color: white; }

        .inp {
            width: 100%; padding: 10px 13px;
            background: var(--bg-input); border: 1px solid var(--border-md);
            border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-body);
            color: var(--txt); outline: none; transition: all var(--trans);
        }
        .inp::placeholder { color: var(--txt-4); }
        .inp:hover { border-color: var(--border-str); }
        .inp:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.1px;
        }
        .badge-green  { background: var(--green-soft);  color: var(--green); }
        .badge-red    { background: var(--red-soft);    color: var(--red); }
        .badge-amber  { background: var(--amber-soft);  color: var(--amber); }
        .badge-blue   { background: var(--accent-soft); color: var(--accent); }
        .badge-cyan   { background: var(--cyan-soft);   color: var(--cyan); }
        .badge-purple { background: var(--purple-soft); color: var(--purple); }
        .badge-neutral{ background: var(--bg-hover);    color: var(--txt-3); border: 1px solid var(--border); }

        /* Toast */
        #toast-container {
            position: fixed; bottom: 24px; left: calc(var(--sidebar-w) + 16px);
            z-index: 9999; display: flex; flex-direction: column; gap: 8px;
        }
        .toast {
            padding: 12px 20px; border-radius: var(--radius-md);
            font-size: 13px; font-weight: 500; color: white; font-family: var(--font-body);
            box-shadow: var(--shadow-md); animation: toast-in 0.3s var(--ease-back);
            max-width: 320px; display: flex; align-items: center; gap: 8px;
        }
        @keyframes toast-in { from { opacity:0; transform:translateY(12px) scale(0.97); } to { opacity:1; transform:translateY(0) scale(1); } }
        .toast-success { background: var(--green); }
        .toast-error   { background: var(--red); }
        .toast-info    { background: var(--accent); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-str); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--txt-4); }

        /* Animations */
        @keyframes fade-up { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .anim { animation: fade-up 0.4s var(--ease) both; }
        .anim-d1 { animation-delay: 60ms; }
        .anim-d2 { animation-delay: 120ms; }
        .anim-d3 { animation-delay: 180ms; }
        .anim-d4 { animation-delay: 240ms; }

        /* Shimmer */
        .shimmer {
            background: linear-gradient(90deg, var(--bg-hover) 25%, var(--border) 50%, var(--bg-hover) 75%);
            background-size: 200% 100%; animation: shim 1.6s infinite;
        }
        @keyframes shim { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    </style>
</head>
<body>
    @auth
        <div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sb-logo-area">
                <div class="sb-logo-icon">
                    <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="sb-logo-text">NexusAI</div>
                    <div class="sb-logo-sub">Agent Platform</div>
                </div>
            </div>

            <nav class="sb-nav">
                <div class="sb-section">
                    <div class="sb-section-label">Overview</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
                <div class="sb-section">
                    <div class="sb-section-label">Workspace</div>
                    <a href="{{ route('agent') }}" class="nav-link {{ request()->routeIs('agent') ? 'active' : '' }}">
                        <svg class="nl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/>
                        </svg>
                        AI Agent
                    </a>
                    <a href="{{ route('chat') }}" class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}">
                        <svg class="nl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Conversations
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
            <span class="mob-title">NexusAI</span>
        </div>

        <main class="main-content">
            @yield('content')
        </main>

    @else
        <main>@yield('content')</main>
    @endauth

    <div id="toast-container"></div>

    <script>
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
        const dark = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyTheme(dark);
    })();
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) applyTheme(e.matches);
    });

    function openSidebar() {
        document.getElementById('sidebar')?.classList.add('open');
        document.getElementById('sbOverlay')?.classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar')?.classList.remove('open');
        document.getElementById('sbOverlay')?.classList.remove('show');
    }

    document.getElementById('logoutForm')?.addEventListener('submit', e => {
        if (!confirm('Sign out of NexusAI?')) e.preventDefault();
    });

    window.showToast = function(msg, type = 'info') {
        const el = document.createElement('div');
        el.className = 'toast toast-' + type;
        el.textContent = msg;
        document.getElementById('toast-container').appendChild(el);
        setTimeout(() => {
            el.style.opacity = '0'; el.style.transform = 'translateY(8px)';
            el.style.transition = '0.25s'; setTimeout(() => el.remove(), 260);
        }, 3200);
    };
    </script>
</body>
</html>