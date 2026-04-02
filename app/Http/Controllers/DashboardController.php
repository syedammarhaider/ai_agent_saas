<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Task;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get dashboard stats with fallbacks
            $stats = [
                'total_clients' => Client::count() ?? 0,
                'active_clients' => Client::where('status', 'active')->count() ?? 0,
                'total_conversations' => Conversation::count() ?? 0,
                'resolved_today' => Conversation::where('status', 'closed')
                    ->whereDate('updated_at', now()->toDateString())
                    ->count() ?? 0,
                'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount') ?? 0,
                'pending_tasks' => Task::where('status', 'pending')->count() ?? 0,
                'in_progress_tasks' => Task::where('status', 'in_progress')->count() ?? 0,
                'revenue_this_month' => Invoice::where('status', 'paid')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_amount') ?? 0,
                'total_collected' => Invoice::where('status', 'paid')->sum('total_amount') ?? 0,
                'pending_payment' => Invoice::where('status', 'sent')->sum('total_amount') ?? 0,
            ];

            // Get recent conversations with fallback
            $recentConversations = collect();
            try {
                $recentConversations = Conversation::with(['client'])
                    ->withCount(['messages'])
                    ->latest()
                    ->take(10)
                    ->get();
            } catch (\Exception $e) {
                // Table doesn't exist or other issue
            }

            // Get recent tasks with fallback
            $recentTasks = collect();
            try {
                $recentTasks = Task::with(['client', 'assignedTo'])
                    ->where('status', 'pending')
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                // Table doesn't exist or other issue
            }

            // Get revenue data for chart with fallback
            $revenueData = collect();
            try {
                $revenueData = Invoice::where('status', 'paid')
                    ->selectRaw('DATE_FORMAT(created_at, "%M") as month, SUM(total_amount) as revenue')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->take(12)
                    ->get();
            } catch (\Exception $e) {
                // Table doesn't exist or other issue
            }

            // Get platform distribution with fallback
            $platformData = [
                'whatsapp' => 0,
                'slack' => 0,
                'email' => 0,
            ];
            try {
                $platformData = [
                    'whatsapp' => Conversation::where('platform', 'whatsapp')->count(),
                    'slack' => Conversation::where('platform', 'slack')->count(),
                    'email' => Conversation::where('platform', 'email')->count(),
                ];
            } catch (\Exception $e) {
                // Table doesn't exist or other issue
            }

            // Get satisfaction score
            $satisfactionScore = 100;
            try {
                $satisfactionScore = $this->calculateSatisfactionScore();
            } catch (\Exception $e) {
                // Table doesn't exist or other issue
            }

            // Check if we have real data, otherwise use simple view
            if ($stats['total_clients'] == 0 && $stats['total_conversations'] == 0) {
                return view('dashboard_simple');
            }

            return view('dashboard', compact(
                'stats', 
                'recentConversations', 
                'recentTasks', 
                'revenueData', 
                'platformData',
                'satisfactionScore'
            ));
            
        } catch (\Exception $e) {
            // Fallback to simple dashboard if any error occurs
            return view('dashboard');
        }
    }

    private function calculateSatisfactionScore(): float
    {
        // Simulate satisfaction score based on recent conversations
        $totalConversations = Conversation::where('status', 'closed')->count();
        $escalatedConversations = Conversation::where('status', 'escalated')->count();
        
        if ($totalConversations === 0) return 100;
        
        $escalationRate = ($escalatedConversations / $totalConversations) * 100;
        return max(0, 100 - $escalationRate);
    }

    public function getStats()
    {
        $stats = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'total_conversations' => Conversation::count(),
            'resolved_today' => Conversation::where('status', 'closed')
                ->whereDate('updated_at', now()->toDateString())
                ->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
        ];

        return response()->json($stats);
    }

    public function getConversations(Request $request)
    {
        $query = Conversation::with(['client'])
            ->withCount(['messages'])
            ->latest();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('platform')) {
            $query->where('platform', $request->platform);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $conversations = $query->paginate(20);

        return response()->json($conversations);
    }

    public function getMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->with('conversation.client')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'agent',
            'content' => $request->content,
        ]);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => $message->load('conversation.client'),
        ]);
    }

    public function createTask(Request $request, $conversationId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date|after:today',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        $task = Task::create([
            'conversation_id' => $conversationId,
            'client_id' => $conversation->client_id,
            'assigned_to' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'task' => $task->load('client'),
        ]);
    }

    public function updateTaskStatus(Request $request, $taskId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $task = Task::findOrFail($taskId);
        $task->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }
}
