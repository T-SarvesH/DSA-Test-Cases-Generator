<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class GeminiController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function showForm()
    {
        return view('gemini-form');
    }

    public function generateText(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);
    
        $response = $this->geminiService->generateText($request->input('prompt'));
    
        // Check if the response is correctly received
        if ($response === null || empty($response)) {
            $response = 'No response received from Gemini API.';
        }
    
        return view('gemini-form', [
            'prompt' => $request->input('prompt'),
            'response' => $response
        ]);
    }
}
