<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\PlatformIntegration;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('user_id', Auth::id())->withCount('conversations')->paginate(20);
        return view('clients', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'nullable|string|max:20',
        ]);

        Client::create(array_merge($request->all(), ['user_id' => Auth::id()]));

        return redirect()->route('clients')->with('success', 'Client created successfully');
    }

    public function show(Client $client)
    {
        $client->load(['conversations.messages', 'platformIntegrations']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        if ($client->user_id !== Auth::id()) abort(403);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if ($client->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($request->all());

        return redirect()->route('clients')->with('success', 'Client updated successfully');
    }

    public function destroy(Client $client)
    {
        if ($client->user_id !== Auth::id()) abort(403);
        $client->delete();
        return redirect()->route('clients')->with('success', 'Client deleted successfully');
    }

    public function addPlatform(Request $request, Client $client)
    {
        $request->validate([
            'platform' => 'required|in:whatsapp,slack,email',
            'credentials' => 'required|json',
        ]);

        PlatformIntegration::updateOrCreate(
            ['client_id' => $client->id, 'platform' => $request->platform],
            ['credentials' => $request->credentials]
        );

        return back()->with('success', 'Platform added');
    }

    public function updatePlatform(Request $request, Client $client, $integrationId)
    {
        $integration = PlatformIntegration::findOrFail($integrationId);
        $integration->update(['credentials' => $request->credentials]);
        return back()->with('success', 'Platform updated');
    }

    public function removePlatform(Client $client, $integrationId)
    {
        $integration = PlatformIntegration::findOrFail($integrationId);
        $integration->delete();
        return back()->with('success', 'Platform removed');
    }

    public function getStats(Client $client)
    {
        return response()->json([
            'conversations_count' => $client->conversations()->count(),
            'messages_count' => $client->conversations()->withCount('messages')->get()->sum('messages_count'),
        ]);
    }
}

