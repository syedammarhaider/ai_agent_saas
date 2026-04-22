<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Authentication Routes - Admin only
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Protected Authentication Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Agent Routes
    Route::prefix('/agent')->group(function () {
        Route::get('/health', [AgentController::class, 'health']);
        Route::post('/chat', [AgentController::class, 'chat']);
        Route::get('/history/{userId}', [AgentController::class, 'history']);
        Route::delete('/history', [AgentController::class, 'clearHistory']);
        Route::get('/users', [AgentController::class, 'users']);
    });

    // Conversation Routes
    Route::prefix('/conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'list']);
        Route::get('/{id}/messages', [ConversationController::class, 'messages']);
        Route::post('/{id}/reply', [ConversationController::class, 'reply']);
    });

    // Client Routes
    Route::prefix('/clients')->group(function () {
        Route::get('/', [ClientController::class, 'list']);
        Route::post('/', [ClientController::class, 'store']);
        Route::put('/{id}', [ClientController::class, 'update']);
        Route::patch('/{id}/status', [ClientController::class, 'updateStatus']);
        Route::delete('/{id}', [ClientController::class, 'destroy']);
        Route::get('/stats', [ClientController::class, 'stats']);
        Route::get('/{id}/conversations', [ClientController::class, 'conversations']);
    });

    // Invoice Routes
    Route::prefix('/invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/', [InvoiceController::class, 'store']);
        Route::get('/stats', [InvoiceController::class, 'stats']);
        Route::put('/{id}', [InvoiceController::class, 'update']);
        Route::delete('/{id}', [InvoiceController::class, 'destroy']);
        Route::post('/{id}/export-pdf', [InvoiceController::class, 'exportPdf']);
    });

    // Settings Routes
    Route::prefix('/settings')->group(function () {
        Route::get('/profile', [SettingsController::class, 'profile']);
        Route::put('/profile', [SettingsController::class, 'updateProfile']);
        Route::put('/password', [SettingsController::class, 'password']);
        Route::get('/api-keys', [SettingsController::class, 'apiKeys']);
        Route::put('/api-keys', [SettingsController::class, 'updateApiKeys']);
        Route::get('/whatsapp', [SettingsController::class, 'whatsapp']);
        Route::put('/whatsapp', [SettingsController::class, 'updateWhatsapp']);
    });
});
