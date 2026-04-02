<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\AgentLog;

class AgentController extends Controller
{
    private $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function status(Request $request)
    {
        $user = $request->user();
        
        $tasksToday = $user->agentLogs()->whereDate('created_at', now())->count();
        $successRate = $this->calculateSuccessRate($user);
        
        return response()->json([
            'status' => 'running', // In a real app, you'd check actual agent status
            'tasks_today' => $tasksToday,
            'success_rate' => $successRate,
            'last_active' => $user->agentLogs()->latest()->first()?->created_at,
        ]);
    }

    public function metrics(Request $request)
    {
        $user = $request->user();
        
        $totalLogs = $user->agentLogs()->count();
        $successful = $user->agentLogs()->where('status', 'success')->count();
        $failed = $user->agentLogs()->where('status', 'failed')->count();
        $processing = $user->agentLogs()->where('status', 'processing')->count();
        
        return response()->json([
            'total' => $totalLogs,
            'successful' => $successful,
            'failed' => $failed,
            'processing' => $processing,
        ]);
    }

    public function logs(Request $request)
    {
        $user = $request->user();
        
        $logs = $user->agentLogs()
            ->latest()
            ->paginate(50);
        
        return response()->json([
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    public function toggle(Request $request)
    {
        // In a real application, you'd implement actual agent start/stop logic
        $isRunning = $request->input('current_status') === 'running';
        $newStatus = $isRunning ? 'stopped' : 'running';
        
        // Log the action
        AgentLog::create([
            'action' => 'toggle_agent',
            'message' => "Agent status changed to {$newStatus}",
            'status' => 'success',
            'user_id' => $request->user()->id,
        ]);
        
        return response()->json([
            'status' => $newStatus,
            'message' => "Agent is now {$newStatus}"
        ]);
    }

    public function execute(Request $request)
    {
        $action = $request->input('action');
        $data = $request->input('data', []);
        
        // Create a processing log
        $log = AgentLog::create([
            'action' => $action,
            'message' => 'Executing task...',
            'status' => 'processing',
            'data' => $data,
            'user_id' => $request->user()->id,
        ]);
        
        try {
            // Simulate task execution based on action
            $result = $this->executeTask($action, $data);
            
            // Update log with success
            $log->update([
                'status' => 'success',
                'message' => 'Task completed successfully',
                'data' => array_merge($data, ['result' => $result])
            ]);
            
            return response()->json([
                'message' => 'Task executed successfully',
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            // Update log with failure
            $log->update([
                'status' => 'failed',
                'message' => 'Task failed: ' . $e->getMessage()
            ]);
            
            return response()->json([
                'message' => 'Task execution failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function executeTask($action, $data)
    {
        switch ($action) {
            case 'test_task':
                return [
                    'message' => 'Test task completed',
                    'timestamp' => now(),
                    'data' => $data
                ];
                
            case 'send_email':
                // Simulate sending email
                return [
                    'message' => 'Email sent successfully',
                    'recipient' => $data['recipient'] ?? 'unknown'
                ];
                
            case 'generate_report':
                // Simulate generating report
                return [
                    'message' => 'Report generated',
                    'report_id' => uniqid(),
                    'data' => $data
                ];
                
            default:
                throw new \Exception("Unknown action: {$action}");
        }
    }

    private function calculateSuccessRate($user)
    {
        $totalLogs = $user->agentLogs()->count();
        if ($totalLogs === 0) {
            return 0;
        }

        $successfulLogs = $user->agentLogs()->where('status', 'success')->count();
        return round(($successfulLogs / $totalLogs) * 100, 2);
    }
}
