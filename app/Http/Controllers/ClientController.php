<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /** Page */
    public function index()
    {
        return view('clients');
    }

    /** GET /api/clients */
    public function list(Request $request)
    {
        $q = Client::query();

        if ($s = $request->search) {
            $q->where(fn($x) =>
                $x->where('name',    'like', "%$s%")
                  ->orWhere('email',   'like', "%$s%")
                  ->orWhere('company', 'like', "%$s%")
                  ->orWhere('phone',   'like', "%$s%")
            );
        }

        if ($p = $request->platform) {
            $q->whereRaw('LOWER(channel) = ?', [strtolower($p)]);
        }

        $clients = $q->latest()->get()->map(fn($c) => [
            'id'        => $c->id,
            'name'      => $c->name,
            'email'     => $c->email,
            'phone'     => $c->phone,
            'company'   => $c->company,
            'status'    => $c->status  ?? 'active',
            'channel'   => strtolower($c->channel ?? 'api'),
            'platforms' => [strtolower($c->channel ?? 'api')],
            'created_at'=> $c->created_at,
        ]);

        return response()->json(['clients' => $clients]);
    }

    /** GET /api/clients/stats */
    public function stats()
    {
        return response()->json([
            'total'  => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'wa'     => Client::whereRaw('LOWER(channel) = ?', ['whatsapp'])->count(),
            'plats'  => Client::selectRaw('LOWER(channel) as c')->distinct()->pluck('c')->filter()->count() ?: 1,
        ]);
    }

    /** POST /api/clients */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:clients,email',
            'phone'   => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'channel' => 'nullable|string',
        ]);

        $client = Client::create($data + [
            'user_id' => Auth::id(),
            'status'  => 'active',
            'channel' => strtolower($data['channel'] ?? 'api'),
        ]);

        return response()->json($client, 201);
    }

    /** PUT /api/clients/{id} */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $data   = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => "required|email|unique:clients,email,$id",
            'phone'   => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'status'  => 'nullable|in:active,inactive',
            'channel' => 'nullable|string',
        ]);
        $client->update($data);
        return response()->json($client);
    }

    /** DELETE /api/clients/{id} */
    public function destroy($id)
    {
        Client::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    /** GET /api/clients/{id}/conversations */
    public function conversations($id)
    {
        $client = Client::findOrFail($id);
        $convs  = $client->conversations()
            ->with(['messages' => fn($q) => $q->latest()->limit(1)])
            ->latest()->get()
            ->map(fn($c) => [
                'id'           => $c->id,
                'platform'     => strtolower($c->platform ?? 'api'),
                'status'       => $c->status ?? 'open',
                'last_message' => $c->messages->first()?->content,
                'updated_at'   => $c->updated_at,
            ]);
        return response()->json(['conversations' => $convs]);
    }
}