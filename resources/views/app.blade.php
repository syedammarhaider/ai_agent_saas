<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Agent SaaS') }}</title>
    
    <!-- Load built CSS -->
    <link rel="stylesheet" href="/build/assets/app-DJ4awAEj.css">
    
    <style>
    /* Additional Blade-specific styles */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { height: 100%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    body.auth-page .sidebar { display: none; }
    .dark { color-scheme: dark; }
    
    /* Sidebar */
    .sidebar { @apply w-64 bg-white shadow-lg dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 fixed h-full z-50 lg:translate-x-0 transform -translate-x-full lg:translate-x-0 transition-transform; }
    .nav-link { @apply flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors w-full; }
    .nav-link:hover { @apply bg-gray-100 dark:bg-gray-700; }
    .nav-link.active { @apply bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-400; }
    
    /* Card */
    .card { @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6; }
    
    /* Button */
    .btn { @apply inline-flex items-center justify-center font-medium rounded-lg text-sm px-5 py-2.5 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500; }
    .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white; }
    .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white; }
    
    /* Input */
    .form-input { @apply w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500; }
    
    /* Layout */
    .main-content { @apply ml-0 lg:ml-64 p-6 transition-all; }
    
    /* Mobile menu toggle */
    .mobile-menu-btn { @apply lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 {{ request()->routeIs('login*', 'register') ? 'auth-page' : '' }}">
    <!-- React App Container -->
    <div id="app"></div>
    
    <!-- Load React App -->
    <script src="/build/assets/main-RYw2kGSj.js"></script>
</body>
</html>
