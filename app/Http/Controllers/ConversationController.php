<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /** Page */
    public function chat()
    {
        return view('chat');
    }

    /** GET /api/conversations */
    public function list(Request $request)
    {
        // No user_id filter — all webhook-created conversations are visible
        $q = Conversation::with(['client', 'messages' => fn($x) => $x->latest()->limit(1)])->latest();

        if ($p = $request->platform) {
            $q->whereRaw('LOWER(platform) = ?', [strtolower($p)]);
        }

        if ($s = $request->search) {
            $q->whereHas('client', fn($x) => $x->where('name', 'like', "%$s%"));
        }

        $convs = $q->get()->map(fn($c) => [
            'id'           => $c->id,
            'client_name'  => $c->client?->name ?? ($c->title ?? 'Conv #' . $c->id),
            'platform'     => strtolower($c->platform ?? 'api'),
            'status'       => $c->status ?? 'open',
            'last_message' => $c->messages->first()?->content,
            'updated_at'   => $c->updated_at,
            'created_at'   => $c->created_at,
        ]);

        return response()->json(['conversations' => $convs]);
    }

    /** GET /api/conversations/{id}/messages */
    public function messages(Request $request, $id)
    {
        $msgs = Conversation::findOrFail($id)->messages()->oldest()->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'content'     => $m->content,
                'sender_type' => $m->sender_type ?? 'client',
                'created_at'  => $m->created_at,
            ]);

        return response()->json(['messages' => $msgs]);
    }

    /** POST /api/conversations/{id}/reply */
    public function reply(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:4000']);

        $conv = Conversation::findOrFail($id);
        $msg  = $conv->messages()->create([
            'content'     => $request->content,
            'sender_type' => 'agent',
        ]);
        $conv->touch();

        return response()->json(['success' => true, 'message' => $msg]);
    }

    /** PUT /api/conversations/{id}/status */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:open,closed,pending']);
        Conversation::findOrFail($id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }
}