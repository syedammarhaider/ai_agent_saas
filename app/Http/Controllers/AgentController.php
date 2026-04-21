<?php
// app/Http/Controllers/AgentController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    private string $pythonUrl;

    public function __construct()
    {
        $this->pythonUrl = rtrim(
            env('PYTHON_BACKEND_URL', env('AGENT_URL', 'http://127.0.0.1:8003')),
            '/'
        );
    }

    /** Page view */
    public function index()
    {
        return view('agent');
    }

    /**
     * Health check
     * GET /api/agent/health
     */
    public function health()
    {
        try {
            $res = Http::timeout(4)->get("{$this->pythonUrl}/health");
            if ($res->successful()) {
                return response()->json(['status' => 'online']);
            }
            return response()->json(['status' => 'degraded'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'offline'], 200);
        }
    }

    /**
     * Send message to AI agent
     * POST /api/agent/chat
     */
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'user_id'  => 'required|string|max:200',
            'message'  => 'required|string|max:4000',
            'platform' => 'nullable|string|in:web,whatsapp,slack,api',
        ]);

        $userId   = $validated['user_id'];
        $message  = trim($validated['message']);
        $platform = $validated['platform'] ?? 'web';

        if (empty($message)) {
            return response()->json(['reply' => 'Please type a message.'], 422);
        }

        try {
            $response = Http::timeout(90)
                ->retry(2, 3000)
                ->post("{$this->pythonUrl}/chat", [
                    'user_id'  => $userId,
                    'message'  => $message,
                    'platform' => $platform,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Normalize reply field — agent might return different keys
                $reply = $data['reply']
                    ?? $data['response']
                    ?? $data['message']
                    ?? $data['text']
                    ?? $data['content']
                    ?? null;

                if (empty($reply)) {
                    Log::warning('Python backend empty reply', ['data' => $data]);
                    return response()->json([
                        'reply' => 'I processed your request but had trouble forming a response. Please try again.',
                    ]);
                }

                // Strip any raw technical metadata the Python agent might inject
                $reply = $this->cleanReply($reply);

                return response()->json([
                    'reply'     => $reply,
                    'user_id'   => $userId,
                    'platform'  => $platform,
                    'timestamp' => now()->toISOString(),
                ]);
            }

            Log::error('Python backend non-200', [
                'status' => $response->status(),
                'url'    => $this->pythonUrl,
            ]);

            return response()->json([
                'reply' => 'The AI service encountered an issue. Please try again in a moment.',
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Cannot connect to Python backend: ' . $e->getMessage());

            return response()->json([
                'reply' => "I'm currently unable to reach the AI backend. Please make sure the Python server is running:\n\n`uvicorn main:app --host 0.0.0.0 --port 8003`",
            ]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json([
                'reply' => 'The request timed out. The AI may be processing a complex query — please try again.',
            ]);

        } catch (\Exception $e) {
            Log::error('AgentController::chat error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'An unexpected error occurred. Please try again.',
            ]);
        }
    }

    /**
     * Get conversation history
     * GET /api/agent/history/{userId}
     */
    public function history(Request $request, string $userId)
    {
        try {
            $res = Http::timeout(10)->get("{$this->pythonUrl}/history/" . urlencode($userId));

            if ($res->successful()) {
                $data = $res->json();
                // Normalize: clean all agent messages
                if (isset($data['messages'])) {
                    $data['messages'] = array_map(function ($msg) {
                        if (($msg['role'] ?? '') === 'assistant') {
                            $msg['content'] = $this->cleanReply($msg['content'] ?? '');
                        }
                        return $msg;
                    }, $data['messages']);
                }
                return response()->json($data);
            }

            return response()->json(['messages' => []]);

        } catch (\Exception $e) {
            return response()->json(['messages' => []]);
        }
    }

    /**
     * Clear conversation
     * DELETE /api/agent/history
     */
    public function clearHistory(Request $request)
    {
        $userId = $request->input('user_id');
        if (empty($userId)) {
            return response()->json(['success' => false, 'message' => 'user_id required'], 422);
        }

        try {
            Http::timeout(6)->delete("{$this->pythonUrl}/history/{$userId}");
        } catch (\Exception $e) {
            // Non-critical
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get all users
     * GET /api/agent/users
     */
    public function users()
    {
        try {
            $res = Http::timeout(8)->get("{$this->pythonUrl}/history");
            return $res->successful()
                ? response()->json($res->json())
                : response()->json(['users' => []]);
        } catch (\Exception $e) {
            return response()->json(['users' => []]);
        }
    }

    /**
     * Remove technical jargon and DS artifacts from AI replies.
     * Makes responses natural and human-readable.
     */
    private function cleanReply(string $reply): string
    {
        // Remove common technical prefixes the Python agent might add
        $patterns = [
            '/^(AI Agent:|Agent:|Bot:|Response:|Reply:|Output:)\s*/i',
            '/\[DEBUG:.*?\]/s',
            '/\[SYSTEM:.*?\]/s',
            '/```json\s*\{.*?\}\s*```/s',  // raw JSON blocks
        ];

        foreach ($patterns as $pattern) {
            $reply = preg_replace($pattern, '', $reply);
        }

        // Trim whitespace
        $reply = trim($reply);

        // If somehow still empty
        if (empty($reply)) {
            $reply = 'I understand. How can I help you further?';
        }

        return $reply;
    }
}