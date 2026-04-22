<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ClientEmailService
{
    /**
     * Send status change notification to client
     */
    public function sendStatusChangeEmail(Client $client, string $oldStatus, string $newStatus): bool
    {
        try {
            $statusMessages = [
                'in_progress' => [
                    'subject' => '🚀 Your Project is Now In Progress!',
                    'title' => 'Project Started',
                    'message' => 'Great news! We\'ve started working on your project. Our team is dedicated to delivering excellent results.',
                    'color' => '#4F46E5',
                ],
                'completed' => [
                    'subject' => '✅ Your Project is Complete!',
                    'title' => 'Project Completed',
                    'message' => 'Congratulations! Your project has been successfully completed. Thank you for choosing DS Technologies.',
                    'color' => '#059669',
                ],
                'cancelled' => [
                    'subject' => '❌ Project Status Update',
                    'title' => 'Project Cancelled',
                    'message' => 'Your project has been cancelled. If you have any questions, please don\'t hesitate to reach out to us.',
                    'color' => '#DC2626',
                ],
            ];

            $statusInfo = $statusMessages[$newStatus] ?? $statusMessages['in_progress'];

            $data = [
                'client_name' => $client->name,
                'old_status' => ucfirst(str_replace('_', ' ', $oldStatus)),
                'new_status' => ucfirst(str_replace('_', ' ', $newStatus)),
                'project_details' => $client->project_details,
                'status_title' => $statusInfo['title'],
                'status_message' => $statusInfo['message'],
                'status_color' => $statusInfo['color'],
            ];

            Mail::send('emails.status-change', $data, function ($message) use ($client, $statusInfo) {
                $message->to($client->email, $client->name)
                    ->subject($statusInfo['subject']);
            });

            Log::info("Status change email sent to {$client->email}");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send status email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send custom message to client
     */
    public function sendCustomMessage(Client $client, string $messageContent): bool
    {
        try {
            $data = [
                'client_name' => $client->name,
                'message_content' => $messageContent,
                'project_details' => $client->project_details,
            ];

            Mail::send('emails.custom-message', $data, function ($message) use ($client) {
                $message->to($client->email, $client->name)
                    ->subject('Message from DS Technologies');
            });

            // Update last contacted timestamp
            $client->update(['last_contacted_at' => now()]);

            Log::info("Custom message sent to {$client->email}");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send custom message: " . $e->getMessage());
            return false;
        }
    }
}
