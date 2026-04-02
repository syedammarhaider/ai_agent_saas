<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Conversation;
use App\Models\AgentLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // Create sample clients
        $clients = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1 555 0123',
                'company' => 'Acme Corp',
                'status' => 'active',
                'platforms' => ['whatsapp', 'email'],
                'notes' => 'Key client, priority support',
                'total_revenue' => 5400,
                'open_tasks' => 3,
                'open_conversations' => 2,
                'user_id' => $user->id,
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@techstartup.com',
                'phone' => '+1 555 0456',
                'company' => 'Tech Startup',
                'status' => 'active',
                'platforms' => ['slack', 'email'],
                'notes' => 'Early stage startup, flexible pricing',
                'total_revenue' => 3200,
                'open_tasks' => 1,
                'open_conversations' => 4,
                'user_id' => $user->id,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'mchen@enterprise.com',
                'company' => 'Enterprise Client',
                'status' => 'active',
                'platforms' => ['whatsapp', 'slack', 'email'],
                'notes' => 'Large enterprise, requires custom features',
                'total_revenue' => 12500,
                'open_tasks' => 5,
                'open_conversations' => 3,
                'user_id' => $user->id,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily@design.co',
                'phone' => '+1 555 0789',
                'company' => 'Design Co',
                'status' => 'inactive',
                'platforms' => ['email'],
                'notes' => 'Project completed, follow up for new work',
                'total_revenue' => 1800,
                'open_tasks' => 0,
                'open_conversations' => 1,
                'user_id' => $user->id,
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'rjohnson@consulting.com',
                'phone' => '+1 555 0234',
                'company' => 'Consulting Firm',
                'status' => 'active',
                'platforms' => ['slack', 'telegram'],
                'notes' => 'B2B consulting, high value client',
                'total_revenue' => 7800,
                'open_tasks' => 2,
                'open_conversations' => 2,
                'user_id' => $user->id,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(['email' => $clientData['email']], $clientData);
        }

        // Create sample invoices
        $invoices = [
            [
                'invoice_number' => 'INV-001',
                'amount' => 1200,
                'status' => 'paid',
                'due_date' => '2024-01-15',
                'created_at' => '2024-01-01',
'line_items' => json_encode([['description' => 'Development Services', 'quantity' => 1, 'unit_price' => 1200, 'total' => 1200]]),
                'tax_amount' => 0,
                'total_amount' => 1200,
                'client_id' => 1,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-002',
                'amount' => 800,
                'status' => 'paid',
                'due_date' => '2024-01-20',
                'created_at' => '2024-01-05',
'line_items' => json_encode([['description' => 'Consulting', 'quantity' => 8, 'unit_price' => 100, 'total' => 800]]),
                'tax_amount' => 0,
                'total_amount' => 800,
                'notes' => 'Monthly consulting retainer',
                'client_id' => 2,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-003',
                'amount' => 2500,
                'status' => 'sent',
                'due_date' => '2024-02-01',
                'created_at' => '2024-01-10',
'line_items' => json_encode([['description' => 'Custom Development', 'quantity' => 1, 'unit_price' => 2500, 'total' => 2500]]),
                'tax_amount' => 0,
'total_amount' => 2500,
                'client_id' => 1,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-004',
                'amount' => 600,
                'status' => 'paid',
                'due_date' => '2024-01-25',
                'created_at' => '2024-01-12',
                'items' => [['description' => 'Design Services', 'quantity' => 1, 'unit_price' => 600, 'total' => 600]],
                'client_id' => 4,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-005',
                'amount' => 1800,
                'status' => 'sent',
                'due_date' => '2024-02-05',
                'created_at' => '2024-01-15',
                'items' => [['description' => 'Support Package', 'quantity' => 1, 'unit_price' => 1800, 'total' => 1800]],
                'notes' => 'Quarterly support package',
                'client_id' => 5,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-006',
                'amount' => 450,
                'status' => 'sent',
                'due_date' => '2024-02-10',
                'created_at' => '2024-01-18',
                'items' => [['description' => 'Additional Features', 'quantity' => 1, 'unit_price' => 450, 'total' => 450]],
                'client_id' => 1,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-007',
                'amount' => 320,
                'status' => 'paid',
                'due_date' => '2024-01-30',
                'created_at' => '2024-01-20',
                'items' => [['description' => 'Training Session', 'quantity' => 2, 'unit_price' => 160, 'total' => 320]],
                'client_id' => 2,
                'user_id' => $user->id,
            ],
            [
                'invoice_number' => 'INV-008',
                'amount' => 1500,
                'status' => 'paid',
                'due_date' => '2024-02-15',
                'created_at' => '2024-01-22',
                'items' => [['description' => 'Maintenance Contract', 'quantity' => 1, 'unit_price' => 1500, 'total' => 1500]],
                'notes' => 'Annual maintenance',
                'client_id' => 3,
                'user_id' => $user->id,
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::firstOrCreate(['invoice_number' => $invoiceData['invoice_number']], $invoiceData);
        }

        // Create sample conversations
        for ($i = 1; $i <= 10; $i++) {
            Conversation::create([
                'title' => "Conversation #{$i}",
                'client_id' => rand(1, 5),
                'user_id' => $user->id,
                'status' => 'active',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create sample agent logs
        $activities = [
            ['action' => 'New message received from John Doe', 'message' => 'Client inquiry about project status', 'status' => 'success', 'client_name' => 'John Doe', 'platform' => 'whatsapp', 'activity_type' => 'message_sent'],
            ['action' => 'Task created: Fix API integration issue', 'message' => 'Automated task creation from conversation', 'status' => 'success', 'client_name' => 'Acme Corp', 'platform' => 'slack', 'activity_type' => 'task_created'],
            ['action' => 'Invoice #1234 sent to Sarah Wilson', 'message' => 'Monthly invoice generated and sent', 'status' => 'success', 'client_name' => 'Sarah Wilson', 'platform' => 'email', 'activity_type' => 'invoice_sent'],
            ['action' => 'Query resolved for Tech Startup Inc', 'message' => 'Support ticket closed successfully', 'status' => 'success', 'client_name' => 'Tech Startup', 'platform' => 'whatsapp', 'activity_type' => 'query_resolved'],
            ['action' => 'Escalation triggered for urgent issue', 'message' => 'High priority issue escalated to human agent', 'status' => 'warning', 'client_name' => 'Enterprise Client', 'platform' => 'slack', 'activity_type' => 'escalation'],
        ];

        foreach ($activities as $activity) {
            AgentLog::create(array_merge($activity, [
                'user_id' => $user->id,
                'created_at' => now()->subMinutes(rand(5, 60)),
            ]));
        }
    }
}
