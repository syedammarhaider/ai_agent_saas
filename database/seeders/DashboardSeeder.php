<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\AgentLog;
use App\Models\Invoice;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) return;


// Manually create data
Client::create([
    'user_id' => $user->id,
    'name' => 'Acme Corp',
    'email' => 'contact@acme.com',
    'phone' => '+1-555-0123',
]);
Client::create([
    'user_id' => $user->id,
    'name' => 'Tech Startup',
    'email' => 'hello@techstart.com',
    'phone' => '+1-555-0456',
]);
    }
}
