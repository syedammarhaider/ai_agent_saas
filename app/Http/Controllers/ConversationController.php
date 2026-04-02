<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Client;

class ConversationController extends Controller
{
    private $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(Request $request)
    {
        $conversations = $request->user()
            ->conversations()
            ->with('client')
            ->latest()
            ->get();

        return response()->json([
            'data' => $conversations
        ]);
    }

    public function show(Request $request, $id)
    {
        $conversation = $request->user()
            ->conversations()
            ->with('client')
            ->findOrFail($id);

        $messages = $conversation->messages()
            ->oldest()
            ->get();

        return response()->json($conversation);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $conversation = $request->user()->conversations()->create([
            'title' => $request->title,
            'client_id' => $request->client_id,
        ]);

        return response()->json($conversation, 201);
    }

    public function destroy(Request $request, $id)
    {
        $conversation = $request->user()
            ->conversations()
            ->findOrFail($id);

        $conversation->delete();

        return response()->json([
            'message' => 'Conversation deleted successfully'
        ]);
    }

    public function messages(Request $request, $id)
    {
        $conversation = $request->user()
            ->conversations()
            ->findOrFail($id);

        $messages = $conversation->messages()
            ->oldest()
            ->get();

        return response()->json([
            'data' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'content' => 'required|string',
            'conversation_id' => 'nullable|exists:conversations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $conversation = null;

            // Find or create conversation
            if ($request->conversation_id) {
                $conversation = $user->conversations()->findOrFail($request->conversation_id);
            } else {
                // Create new conversation
                $conversation = $user->conversations()->create([
                    'title' => substr($request->content, 0, 50) . '...',
                ]);
            }

            // Save user message
            $userMessage = $conversation->messages()->create([
                'content' => $request->content,
                'role' => 'user',
            ]);

            // Get conversation history for context
            $history = $conversation->messages()
                ->oldest()
                ->take(10) // Last 10 messages for context
                ->get()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->role,
                        'content' => $msg->content
                    ];
                })
                ->toArray();

            // Generate AI response
            $aiResponse = $this->geminiService->generateResponse(
                $request->content,
                $history
            );

            // Save AI message
            $aiMessage = $conversation->messages()->create([
                'content' => $aiResponse,
                'role' => 'assistant',
            ]);

            // Update conversation title if it's the first message
            if ($conversation->messages()->count() === 2) {
                $conversation->update([
                    'title' => substr($request->content, 0, 50) . '...'
                ]);
            }

            return response()->json([
                'message' => $aiResponse,
                'user_message' => $userMessage,
                'ai_message' => $aiMessage,
                'conversation' => $conversation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
