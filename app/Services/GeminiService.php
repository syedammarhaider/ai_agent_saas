<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    }

    public function generateResponse($message, $conversationHistory = [])
    {
        try {
            $contents = [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $message]
                    ]
                ]
            ];

            // Add conversation history if available
            foreach ($conversationHistory as $historyItem) {
                $contents[] = [
                    'role' => $historyItem['role'] === 'user' ? 'user' : 'model',
                    'parts' => [
                        ['text' => $historyItem['content']]
                    ]
                ];
            }

            $response = Http::post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'I apologize, but I couldn\'t generate a response.';
            }

            Log::error('Gemini API Error: ' . $response->body());
            return 'I apologize, but I encountered an error while processing your request.';

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return 'I apologize, but I encountered an error while processing your request.';
        }
    }

    public function testConnection()
    {
        try {
            $response = Http::post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => 'Hello, can you respond with just "API working"?']
                        ]
                    ]
                ]
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
