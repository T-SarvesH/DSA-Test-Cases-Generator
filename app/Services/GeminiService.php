<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GeminiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generateText($prompt)
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=' . $this->apiKey;

    try {
        $response = $this->client->post($url, [
            'json' => [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        // Handle response structure
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';
    } catch (RequestException $e) {
        return 'Error: ' . $e->getMessage();
    }
    }
}
