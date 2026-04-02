{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Agent SaaS') }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           CSS VARIABLES - LIGHT THEME (DEFAULT)
           ============================================ */
        :root {
            /* Primary Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-hover: #f8fafc;
            
            /* Text Colors */
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --text-disabled: #94a3b8;
            
            /* Border Colors */
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --border-focus: #3b82f6;
            
            /* Accent Colors */
            --accent: #3b82f6;
            --accent-dark: #2563eb;
            --accent-light: #60a5fa;
            --accent-muted: rgba(59, 130, 246, 0.08);
            --accent-glow: rgba(59, 130, 246, 0.2);
            
            /* Status Colors */
            --success: #10b981;
            --success-dark: #059669;
            --success-muted: rgba(16, 185, 129, 0.1);
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --danger-muted: rgba(239, 68, 68, 0.1);
            --warning: #f59e0b;
            --warning-dark: #d97706;
            --warning-muted: rgba(245, 158, 11, 0.1);
            --info: #3b82f6;
            --info-muted: rgba(59, 130, 246, 0.1);
            
            /* Shadow */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-glow: 0 0 20px rgba(59, 130, 246, 0.3);
            
            /* Typography */
            --font-display: 'Inter', system-ui, -apple-system, sans-serif;
            
            /* Transitions */
            --transition-fast: 150ms;
            --transition-normal: 250ms;
            --transition-slow: 350ms;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        /* ============================================
           DARK THEME OVERRIDES
           ============================================ */
        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --bg-hover: #2d3a4e;
            
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --text-disabled: #64748b;
            
            --border: #334155;
            --border-strong: #475569;
            --border-focus: #60a5fa;
            
            --accent: #60a5fa;
            --accent-dark: #3b82f6;
            --accent-light: #93c5fd;
            --accent-muted: rgba(96, 165, 250, 0.1);
            --accent-glow: rgba(96, 165, 250, 0.25);
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.4), 0 4px 6px -4px rgb(0 0 0 / 0.4);
        }
        
        /* ============================================
           RESET & BASE STYLES
           ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-display);
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.5;
            transition: background-color var(--transition-normal) var(--ease-out), 
                        color var(--transition-normal) var(--ease-out);
            min-height: 100vh;
        }
        
        /* ============================================
           SIDEBAR STYLES
           ============================================ */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background-color: var(--bg-primary);
            border-right: 1px solid var(--border);
            transform: translateX(-100%);
            transition: transform var(--transition-normal) var(--ease-out);
            z-index: 1000;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
                box-shadow: none;
            }
        }
        
        /* Sidebar Header */
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background-color: var(--bg-primary);
            z-index: 10;
        }
        
        .sidebar-logo {
            font-size: 20px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Navigation Links */
        .nav-menu {
            padding: 16px 12px;
        }
        
        .nav-section {
            margin-bottom: 24px;
        }
        
        .nav-section-title {
            padding: 8px 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 4px 0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-fast) var(--ease-out);
            text-decoration: none;
            position: relative;
        }
        
        .nav-link:hover {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            transform: translateX(4px);
        }
        
        .nav-link.active {
            background-color: var(--accent-muted);
            color: var(--accent);
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background-color: var(--accent);
            border-radius: 0 3px 3px 0;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        /* ============================================
           CARD STYLES
           ============================================ */
        .card {
            background-color: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            transition: all var(--transition-normal) var(--ease-out);
        }
        
        .card:hover {
            border-color: var(--border-strong);
            box-shadow: var(--shadow-md);
        }
        
        /* ============================================
           BUTTON STYLES
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-fast) var(--ease-out);
            border: none;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        
        .btn-secondary:hover {
            background-color: var(--bg-hover);
            border-color: var(--border-strong);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background-color: var(--danger-muted);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .btn-danger:hover {
            background-color: var(--danger);
            color: white;
            border-color: var(--danger);
        }
        
        /* ============================================
           INPUT STYLES
           ============================================ */
        .input-field {
            width: 100%;
            padding: 12px 14px;
            background-color: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-primary);
            transition: all var(--transition-fast) var(--ease-out);
        }
        
        .input-field:hover {
            border-color: var(--border-strong);
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-muted);
        }
        
        /* ============================================
           BADGE STYLES
           ============================================ */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .badge-success {
            background-color: var(--success-muted);
            color: var(--success);
        }
        
        .badge-danger {
            background-color: var(--danger-muted);
            color: var(--danger);
        }
        
        .badge-warning {
            background-color: var(--warning-muted);
            color: var(--warning);
        }
        
        .badge-info {
            background-color: var(--accent-muted);
            color: var(--accent);
        }
        
        .badge-neutral {
            background-color: var(--bg-tertiary);
            color: var(--text-muted);
        }
        
        /* ============================================
           THEME TOGGLE
           ============================================ */
        .theme-toggle {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: var(--bg-primary);
            border: 1px solid var(--border);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-normal) var(--ease-out);
            z-index: 1000;
            box-shadow: var(--shadow-md);
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
            border-color: var(--accent);
            box-shadow: var(--shadow-glow);
        }
        
        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            margin-left: 0;
            padding: 24px;
            transition: margin-left var(--transition-normal) var(--ease-out);
            min-height: 100vh;
        }
        
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 280px;
            }
        }
        
        /* ============================================
           MOBILE MENU BUTTON
           ============================================ */
        .mobile-menu-btn {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1001;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background-color: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: all var(--transition-fast) var(--ease-out);
            box-shadow: var(--shadow-sm);
        }
        
        .mobile-menu-btn:hover {
            border-color: var(--accent);
            transform: scale(1.05);
        }
        
        @media (min-width: 1024px) {
            .mobile-menu-btn {
                display: none;
            }
        }
        
        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .space-y-1 > * + * { margin-top: 4px; }
        .space-y-2 > * + * { margin-top: 8px; }
        .space-y-3 > * + * { margin-top: 12px; }
        .space-y-4 > * + * { margin-top: 16px; }
        .space-y-5 > * + * { margin-top: 20px; }
        .space-y-6 > * + * { margin-top: 24px; }
        
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 4px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .gap-5 { gap: 20px; }
        
        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        
        @media (max-width: 768px) {
            .grid-cols-2, .grid-cols-3, .grid-cols-4 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
        
        .hidden { display: none; }
        
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .mt-5 { margin-top: 20px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-5 { margin-bottom: 20px; }
        
        .text-xs { font-size: 11px; }
        .text-sm { font-size: 13px; }
        .text-base { font-size: 15px; }
        .text-lg { font-size: 17px; }
        .text-xl { font-size: 20px; }
        .text-2xl { font-size: 24px; }
        .text-3xl { font-size: 30px; }
        
        .font-normal { font-weight: 400; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        
        .w-full { width: 100%; }
        .w-auto { width: auto; }
        
        .rounded-lg { border-radius: 12px; }
        .rounded-xl { border-radius: 16px; }
        .rounded-2xl { border-radius: 20px; }
        
        .cursor-pointer { cursor: pointer; }
        .cursor-default { cursor: default; }
        
        .overflow-hidden { overflow: hidden; }
        .overflow-y-auto { overflow-y: auto; }
        .overflow-x-auto { overflow-x: auto; }
        
        .sticky { position: sticky; }
        .top-0 { top: 0; }
        .top-6 { top: 24px; }
        
        /* Animations */
        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-slide-up {
            animation: slide-up 0.3s var(--ease-out) forwards;
        }
        
        .animate-fade-in {
            animation: fade-in 0.2s var(--ease-out) forwards;
        }
        
        .animate-pulse {
            animation: pulse 2s var(--ease-out) infinite;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--border-strong);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
        
        /* Selection */
        ::selection {
            background-color: var(--accent-muted);
            color: var(--accent);
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18]">
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Toggle theme">
        <svg id="themeIcon" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/>
            <path d="M12 7v10c-2.76 0-5-2.24-5-5s2.24-5 5-5z"/>
        </svg>
    </button>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleSidebar()">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>
    
    @auth
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-logo">AI Agent SaaS</h1>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="submit" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
            
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('agent') }}" class="nav-link {{ request()->routeIs('agent') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Agent
                    </a>
                    <a href="{{ route('chat') }}" class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                        Chat
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <a href="{{ route('clients') }}" class="nav-link {{ request()->routeIs('clients') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm8 6v2c0 .55-.45 1-1 1h-2c-.55 0-1-.45-1-1v-2c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"/>
                        </svg>
                        Clients
                    </a>
                    <a href="{{ route('billing') }}" class="nav-link {{ request()->routeIs('billing') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                        </svg>
                        Billing
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    @else
        <main>
            @yield('content')
        </main>
    @endauth
    
    <script>
        // ============================================
        // THEME MANAGEMENT
        // ============================================
        function getThemePreference() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) return savedTheme;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        
        function setTheme(theme) {
            const html = document.getElementById('html-root');
            const icon = document.getElementById('themeIcon');
            
            if (theme === 'dark') {
                html.classList.add('dark');
                icon.innerHTML = '<path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/><path d="M12 7v10c-2.76 0-5-2.24-5-5s2.24-5 5-5z"/>';
            } else {
                html.classList.remove('dark');
                icon.innerHTML = '<path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>';
            }
            
            localStorage.setItem('theme', theme);
        }
        
        function toggleTheme() {
            const html = document.getElementById('html-root');
            const isDark = html.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
        }
        
        // Initialize theme
        const initialTheme = getThemePreference();
        setTheme(initialTheme);
        
        // ============================================
        // SIDEBAR MANAGEMENT
        // ============================================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth < 1024 && sidebar && sidebar.classList.contains('open')) {
                if (!sidebar.contains(event.target) && !mobileBtn.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
        
        // Close sidebar on link click (mobile)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.remove('open');
                }
            });
        });
        
        // ============================================
        // LOGOUT CONFIRMATION
        // ============================================
        const logoutForm = document.getElementById('logoutForm');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to logout?')) {
                    e.preventDefault();
                }
            });
        }
        
        // ============================================
        // ACTIVE LINK HIGHLIGHT
        // ============================================
        function highlightCurrentLink() {
            const currentPath = window.location.pathname;
            const links = document.querySelectorAll('.nav-link');
            
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath || (currentPath === '/' && href === '/dashboard')) {
                    link.classList.add('active');
                } else if (currentPath.startsWith(href) && href !== '/') {
                    link.classList.add('active');
                }
            });
        }
        
        highlightCurrentLink();
        
        // ============================================
        // PREFERS COLOR SCHEME CHANGE LISTENER
        // ============================================
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                setTheme(e.matches ? 'dark' : 'light');
            }
        });
        
        // ============================================
        // ADD TOAST FUNCTIONALITY (Global)
        // ============================================
        window.showToast = function(message, type = 'info') {
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
                animation: slide-up 0.3s var(--ease-out);
                box-shadow: var(--shadow-md);
                max-width: 320px;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };
        
        // ============================================
        // ADD CONFIRM DIALOG FUNCTIONALITY
        // ============================================
        window.showConfirm = function(message, onConfirm, onCancel) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10001;
                animation: fade-in 0.2s var(--ease-out);
            `;
            
            modal.innerHTML = `
                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 20px; padding: 24px; max-width: 400px; width: 90%;">
                    <p style="margin-bottom: 20px; color: var(--text-primary);">${message}</p>
                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <button class="btn-secondary" id="confirmCancel">Cancel</button>
                        <button class="btn-primary" id="confirmOk">OK</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            document.getElementById('confirmOk').onclick = () => {
                modal.remove();
                if (onConfirm) onConfirm();
            };
            
            document.getElementById('confirmCancel').onclick = () => {
                modal.remove();
                if (onCancel) onCancel();
            };
        };
        
        console.log('Layout initialized with theme support');
    </script>
</body>
</html>