<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Page - Client Activity Center */
    public function index()
    {
        // Get recent client activities
        $recentClients = Client::with([
                'conversations' => fn($q) => $q->latest()->limit(1),
                'conversations.messages' => fn($q) => $q->latest()->limit(3),
            ])
            ->latest('updated_at')
            ->limit(12)
            ->get()
            ->map(function ($client) {
                $latestConversation = $client->conversations->first();
                $latestMessage = $latestConversation?->messages->first();
                
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'channel' => strtolower($client->channel),
                    'status' => $client->status,
                    'created_at' => $client->created_at,
                    'updated_at' => $client->updated_at,
                    'latest_message' => $latestMessage?->content,
                    'conversation_count' => $client->conversations->count(),
                    'project_details' => $client->project_details,
                    'last_contacted' => $client->last_contacted_at,
                ];
            });

        // Get client statistics
        $clientStats = [
            'total' => Client::count(),
            'new_this_week' => Client::where('created_at', '>=', now()->subDays(7))->count(),
            'active' => Client::where('status', 'in_progress')->count(),
            'completed' => Client::where('status', 'completed')->count(),
            'with_conversations' => Client::whereHas('conversations')->count(),
        ];

        // Get platform distribution
        $platformStats = Client::selectRaw('LOWER(channel) as platform, COUNT(*) as count')
            ->groupBy('channel')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->platform => $item->count];
            });

        // Get recent client activities (status changes, new clients, etc.)
        $activities = collect();
        
        // Add new clients
        $newClients = Client::latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($client) {
                return [
                    'type' => 'new_client',
                    'client_id' => $client->id,
                    'client_name' => $client->name,
                    'client_email' => $client->email,
                    'channel' => $client->channel,
                    'description' => "New client registered: {$client->name}",
                    'time' => $client->created_at,
                    'icon' => 'user-plus'
                ];
            });
        
        $activities = $activities->merge($newClients);

        return view('dashboard', compact('recentClients', 'clientStats', 'platformStats', 'activities'));
    }

    private function extractProjectDetails($conversation)
    {
        if (!$conversation) return null;
        
        // Get all messages from this conversation
        $messages = $conversation->messages()
            ->where('sender_type', 'client')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get()
            ->pluck('content')
            ->implode(' ');
        
        // Extract project details from messages
        $projectDetails = null;
        
        // Look for keywords that indicate project details
        if (preg_match('/(website|web app|mobile app|ecommerce|react|laravel|python|java)/i', $messages, $matches)) {
            $projectDetails = $messages;
        }
        
        return $projectDetails;
    }

    /**
     * GET /api/dashboard/stats
     * Client-focused statistics for activity center
     */
    public function stats()
    {
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'in_progress')->count();
        $completedClients = Client::where('status', 'completed')->count();
        $newThisWeek = Client::where('created_at', '>=', now()->subDays(7))->count();
        
        // Platform distribution
        $platforms = Client::selectRaw('LOWER(channel) as platform, COUNT(*) as count')
            ->groupBy('channel')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->platform => $item->count];
            });

        return response()->json([
            'stats' => [
                'total_clients'        => $totalClients,
                'active_clients'       => $activeClients,
                'completed_clients'    => $completedClients,
                'new_this_week'        => $newThisWeek,
                'with_conversations'   => Client::whereHas('conversations')->count(),
                'engagement_rate'      => $totalClients > 0 ? round(($activeClients / $totalClients) * 100, 1) : 0,
                'platforms'            => $platforms,
                'satisfaction'         => 94,
                'avg_response'         => '~1.2s',
            ],
        ]);
    }

    /**
     * GET /api/dashboard/messages
     * Client activities and recent interactions
     */
    public function messages()
    {
        // Get recent client activities
        $clients = Client::with([
                'conversations' => fn($q) => $q->latest()->limit(1),
                'conversations.messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->latest('updated_at')
            ->limit(10)
            ->get()
            ->map(fn($c) => [
                'id'           => $c->id,
                'client_name'  => $c->name,
                'client_email' => $c->email,
                'platform'     => strtolower($c->channel),
                'status'       => $c->status,
                'last_message' => $c->conversations->first()?->messages->first()?->content,
                'updated_at'   => $c->updated_at,
                'created_at'   => $c->created_at,
                'conversation_count' => $c->conversations->count(),
            ]);

        return response()->json(['clients' => $clients]);
    }

    /** POST /api/dashboard/test */
    public function quickTest(Request $request)
    {
        return response()->json(['status' => 'ok', 'time' => now()->toISOString()]);
    }
}

