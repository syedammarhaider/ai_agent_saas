<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Page */
    public function index()
    {
        // Get all clients with their conversations and latest message
        $clients = Client::with([
                'conversations' => fn($q) => $q->latest(),
                'conversations.messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->latest()
            ->get()
            ->map(function ($client) {
                $latestConversation = $client->conversations->first();
                $latestMessage = $latestConversation?->messages->first();
                
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'channel' => strtoupper($client->channel),
                    'status' => $client->status,
                    'created_at' => $client->created_at,
                    'latest_message' => $latestMessage?->content,
                    'conversation_count' => $client->conversations->count(),
                    'project_details' => $this->extractProjectDetails($latestConversation),
                ];
            });

        return view('dashboard', compact('clients'));
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
     * NOTE: method was previously named getStats() — routes call stats()
     */
    public function stats()
    {
        return response()->json([
            'stats' => [
                'total_messages'       => Message::count(),
                'active_conversations' => Conversation::where('status', 'open')->count(),
                'resolved_today'       => Conversation::where('status', 'closed')
                                            ->whereDate('updated_at', today())->count(),
                'total_clients'        => Client::count(),
                'satisfaction'         => 94,
                'avg_response'         => '~1.2s',
            ],
        ]);
    }

    /**
     * GET /api/dashboard/messages
     * NOTE: method was missing entirely before
     */
    public function messages()
    {
        $convs = Conversation::with([
                'client',
                'messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->latest()->limit(10)->get()
            ->map(fn($c) => [
                'id'           => $c->id,
                'client_name'  => $c->client?->name ?? ($c->title ?? 'Conv #' . $c->id),
                'platform'     => strtolower($c->platform ?? 'api'),
                'status'       => $c->status ?? 'open',
                'last_message' => $c->messages->first()?->content,
                'updated_at'   => $c->updated_at,
            ]);

        return response()->json(['conversations' => $convs]);
    }

    /** POST /api/dashboard/test */
    public function quickTest(Request $request)
    {
        return response()->json(['status' => 'ok', 'time' => now()->toISOString()]);
    }
}

