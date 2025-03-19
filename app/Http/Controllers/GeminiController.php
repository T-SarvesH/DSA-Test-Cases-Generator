<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class GeminiController extends Controller
{
    public function generateText(Request $request)
    {
        \Log::info('Request Method:', [$request->method()]);  // Debug log
        \Log::info('Request Data:', $request->all());         // Log request data

        $request->validate([
            'prompt' => 'required|string',
        ]);

        $response = $this->geminiService->generateText($request->input('prompt'));

        return response()->json([
            'response' => $response
        ]);
    }

}
