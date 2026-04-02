
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;

// Main app route - serves welcome/login page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('app');

// Auth routes
Route::get('/login', function () {
    if (auth()->check()) return redirect('/dashboard');
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    if (auth()->check()) return redirect('/dashboard');
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes - all serve specific Blade views
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/agent', function () {
        return view('agent');
    })->name('agent');
    
    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');
    
    // Client management routes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    
    // Client platform integration routes
    Route::post('/clients/{client}/platforms', [ClientController::class, 'addPlatform'])->name('clients.platforms.add');
    Route::put('/clients/{client}/platforms/{integration}', [ClientController::class, 'updatePlatform'])->name('clients.platforms.update');
    Route::delete('/clients/{client}/platforms/{integration}', [ClientController::class, 'removePlatform'])->name('clients.platforms.remove');
    Route::get('/clients/{client}/stats', [ClientController::class, 'getStats'])->name('clients.stats');
    
    Route::get('/billing', function () {
        return view('billing');
    })->name('billing');
    
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    
    // API routes for dashboard functionality
    Route::get('/api/stats', [DashboardController::class, 'getStats'])->name('api.stats');
    Route::get('/api/conversations', [DashboardController::class, 'getConversations'])->name('api.conversations');
    Route::get('/api/conversations/{id}/messages', [DashboardController::class, 'getMessages'])->name('api.conversations.messages');
    Route::post('/api/conversations/{id}/messages', [DashboardController::class, 'sendMessage'])->name('api.conversations.messages.send');
    Route::post('/api/conversations/{id}/tasks', [DashboardController::class, 'createTask'])->name('api.conversations.tasks.create');
    Route::put('/api/tasks/{id}/status', [DashboardController::class, 'updateTaskStatus'])->name('api.tasks.update');
});