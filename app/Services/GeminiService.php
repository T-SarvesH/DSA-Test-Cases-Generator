<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GEMINI_API_KEY'); // Ensure your .env has this
    }

    public function generateText($prompt)
    {
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

        try {
            $response = $this->client->post($endpoint, [
                'json' => [
                    'prompt' => $prompt
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            return $data['candidates'][0]['content'] ?? 'No response generated.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
