<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    protected $emailService;

    public function __construct(ClientEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /** Page */
    public function index()
    {
        return view('clients');
    }

    /** GET /api/clients */
    public function list(Request $request)
    {
        $q = Client::with(['conversations.messages' => fn($x) => $x->latest()->limit(1)]);

        if ($s = $request->search) {
            $q->where(fn($x) =>
                $x->where('name',  'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%")
            );
        }

        if ($p = $request->platform) {
            $q->whereRaw('LOWER(channel) = ?', [strtolower($p)]);
        }

        // Show ALL clients - do NOT exclude any channel
        // Previously 'api' was excluded, causing many clients to be hidden.

        $clients = $q->latest()->get()->map(function ($c) {
            // Handle case where conversations might be deleted
            $lastMessage = null;
            if ($c->conversations && $c->conversations->isNotEmpty()) {
                $lastMessage = $c->conversations
                    ->flatMap->messages
                    ->sortByDesc('created_at')
                    ->first();
            }

            return [
                'id'              => $c->id,
                'name'            => $c->name,
                'email'           => $c->email,
                'phone'           => $c->phone,
                'status'          => $c->status,
                'channel'         => strtolower($c->channel),
                'platforms'       => [strtolower($c->channel)],
                'project_details' => $c->project_details,
                'last_message'    => $lastMessage?->content,
                'last_contacted'   => $c->last_contacted_at,
                'created_at'       => $c->created_at,
                'conversation_count' => $c->conversations?->count() ?? 0,
            ];
        });

        return response()->json(['clients' => $clients]);
    }

    /** GET /api/clients/stats */
    public function stats()
    {
        return response()->json([
            'total'  => Client::count(),  // Count ALL clients
            'active' => Client::where('status', 'in_progress')->count(),
            'wa'     => Client::whereRaw('LOWER(channel) = ?', ['whatsapp'])->count(),
            'plats'  => Client::selectRaw('LOWER(channel) as c')
                ->distinct()
                ->pluck('c')
                ->filter()
                ->count() ?: 1,
        ]);
    }

    /** POST /api/clients */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:clients,email',
            'phone'           => 'nullable|string|max:30',
            'channel'         => 'required|in:whatsapp,slack,api,web',
            'project_details' => 'nullable|string',
        ]);

        $client = Client::create($data + [
            'user_id' => Auth::id(),
            'status'  => 'in_progress',
            'channel' => strtolower($data['channel']),
        ]);

        // Send welcome email immediately after client creation
        if ($client->email) {
            try {
                $this->emailService->sendWelcomeEmail($client);
                Log::info("Welcome email sent to {$client->email} after creation.");
            } catch (\Exception $e) {
                Log::error("Failed to send welcome email on client creation: " . $e->getMessage());
            }
        }

        return response()->json($client, 201);
    }

    /** PUT /api/clients/{id} */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => "required|email|unique:clients,email,$id",
            'phone'   => 'nullable|string|max:30',
            'channel' => 'required|in:whatsapp,slack',
            'project_details' => 'nullable|string',
        ]);

        $client->update($data);
        
        return response()->json($client);
    }

    /** PATCH /api/clients/{id}/status */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed,cancelled',
            'message' => 'nullable|string|max:1000', // Optional custom message
        ]);

        $client = Client::findOrFail($id);
        $oldStatus = $client->status;
        $newStatus = $request->status;

        // Update status
        $client->update([
            'status' => $newStatus,
            'last_contacted_at' => now(),
        ]);

        // Send status change email
        $emailSent = $this->emailService->sendStatusChangeEmail($client, $oldStatus, $newStatus);

        // If there's a custom message, send it too
        if ($request->message) {
            $this->emailService->sendCustomMessage($client, $request->message);
        }

        return response()->json([
            'success' => true,
            'client' => $client,
            'email_sent' => $emailSent,
        ]);
    }

    /** POST /api/clients/{id}/send-message */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $client = Client::findOrFail($id);

        // Send custom message
        $emailSent = $this->emailService->sendCustomMessage($client, $request->message);

        return response()->json([
            'success' => true,
            'email_sent' => $emailSent,
            'message' => $emailSent 
                ? 'Message sent successfully to ' . $client->email
                : 'Failed to send message. Please try again.',
        ]);
    }

    /** DELETE /api/clients/{id} */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $client = Client::findOrFail($id);
            
            // Log deletion for audit
            Log::info("Deleting client #{$id}: {$client->name} ({$client->email})");

            // Conversations and messages will cascade delete automatically
            // due to foreign key constraints in migration
            $client->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Client and all associated data deleted successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete client #{$id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client. Please try again.',
            ], 500);
        }
    }

    /** GET /api/clients/{id}/conversations */
    public function conversations($id)
    {
        $client = Client::findOrFail($id);
        
        $convs = $client->conversations()
            ->with(['messages' => fn($q) => $q->latest()->limit(1)])
            ->latest()
            ->get()
            ->map(fn($c) => [
                'id'           => $c->id,
                'platform'     => strtolower($c->platform),
                'status'       => $c->status,
                'last_message' => $c->messages->first()?->content,
                'message_count'=> $c->messages()->count(),
                'updated_at'   => $c->updated_at,
            ]);

        return response()->json(['conversations' => $convs]);
    }
}