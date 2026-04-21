
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AgentController;

// Main app route
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
})->name('app');

// Auth routes
Route::get('/login', fn() => auth()->check() ? redirect('/dashboard') : view('auth.login'))->name('login');
Route::post('/login',  [AuthController::class, 'login']);
Route::get('/register', fn() => auth()->check() ? redirect('/dashboard') : view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {

    // Pages
    Route::get('/dashboard', [DashboardController::class,  'index'])->name('dashboard');
    Route::get('/agent', [AgentController::class, 'index'])->name('agent');
    Route::get('/chat',      [ConversationController::class,'chat'])->name('chat');
    Route::get('/clients',   [ClientController::class,      'index'])->name('clients');
    Route::get('/billing',   fn() => view('billing'))->name('billing');
    Route::get('/settings',  [SettingsController::class,    'index'])->name('settings');

    // Settings
    Route::post('/settings/profile',   [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password',  [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');

    // Dashboard API
    Route::get('/api/dashboard/stats',    [DashboardController::class, 'stats']);
    Route::get('/api/dashboard/messages', [DashboardController::class, 'messages']);
    Route::post('/api/dashboard/test',    [DashboardController::class, 'quickTest']);

    // Clients API
    Route::get('/api/clients/stats',              [ClientController::class, 'stats']);
    Route::get('/api/clients',                    [ClientController::class, 'list']);
    Route::post('/api/clients',                   [ClientController::class, 'store']);
    Route::put('/api/clients/{id}',               [ClientController::class, 'update']);
    Route::delete('/api/clients/{id}',            [ClientController::class, 'destroy']);
    Route::get('/api/clients/{id}/conversations', [ClientController::class, 'conversations']);

    // Conversations / Chat API
    Route::get('/api/conversations',               [ConversationController::class, 'list']);
    Route::get('/api/conversations/{id}/messages', [ConversationController::class, 'messages']);
    Route::post('/api/conversations/{id}/reply',   [ConversationController::class, 'reply']);
    Route::put('/api/conversations/{id}/status',   [ConversationController::class, 'updateStatus']);

    // Agent API
    Route::prefix('api/agent')->group(function () {
        Route::post('/chat', [AgentController::class, 'chat']);
        Route::get('/history/{userId}', [AgentController::class, 'history']);
        Route::delete('/history', [AgentController::class, 'clearHistory']);
    });

    // Clients API
    Route::get('/api/clients/stats',              [ClientController::class, 'stats']);
    Route::get('/api/clients',                    [ClientController::class, 'list']);
    Route::post('/api/clients',                   [ClientController::class, 'store']);
    Route::put('/api/clients/{id}',               [ClientController::class, 'update']);
    Route::delete('/api/clients/{id}',            [ClientController::class, 'destroy']);
    Route::get('/api/clients/{id}/conversations', [ClientController::class, 'conversations']);

    // Conversations / Chat API
    Route::get('/api/conversations',               [ConversationController::class, 'list']);
    Route::get('/api/conversations/{id}/messages', [ConversationController::class, 'messages']);
    Route::post('/api/conversations/{id}/reply',   [ConversationController::class, 'reply']);
    Route::put('/api/conversations/{id}/status',   [ConversationController::class, 'updateStatus']);
}); 