<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

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

            // Detailed response structure checks
            if (isset($result['candidates']) && is_array($result['candidates']) && !empty($result['candidates'])) {
                if (isset($result['candidates'][0]['content']['parts']) && is_array($result['candidates'][0]['content']['parts']) && !empty($result['candidates'][0]['content']['parts'])) {
                    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                        return $result['candidates'][0]['content']['parts'][0]['text'];
                    } else {
                        Log::error('Gemini API: Text not found in response.');
                        return 'Gemini API: Text not found in response.';
                    }
                } else {
                    Log::error('Gemini API: Content parts not found in response.');
                    return 'Gemini API: Content parts not found in response.';
                }
            } else {
                Log::error('Gemini API: Candidates not found in response.');
                return 'Gemini API: Candidates not found in response.';
            }

        } catch (RequestException $e) {
            Log::error('Gemini API Request Error: ' . $e->getMessage());
            return 'Gemini API Request Error: ' . $e->getMessage();
        } catch (\Exception $e) {
            Log::error('Gemini API General Error: ' . $e->getMessage());
            return 'Gemini API General Error: ' . $e->getMessage();
        }
    }
}