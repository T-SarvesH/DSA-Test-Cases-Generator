<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService; // Make sure this namespace is correct
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Support\Str; // Import the Str facade for string helpers

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

    public function codeforcedForm()
    {
        return view('/codeforces/codeforces-form');
    }

    /**
     * Handles AJAX request to fetch problem title and description using GeminiService.
     * Expects 'id' (problem ID) and 'platformName' ('leetcode' or 'codeforces') in the request body.
     * Uses the platform name to specify the coding site in the prompt.
     *
     * @param \Illuminate\Http\Request $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function scrapDesctitle(Request $request)
    {
        try {
            // Ensure the request is AJAX
            if ($request->ajax()) {
                // Validate the input from the JSON body
                $request->validate([
                    'id' => 'required|string', // Problem ID
                    // Validate platformName: must be nullable string, and if present, must be 'leetcode' or 'codeforces'
                    'platformName' => 'nullable|string|in:leetcode,codeforces',
                ]);

                $id = $request->input('id');
                // Get the extracted platform name sent from JavaScript
                $platformName = $request->input('platformName'); // 'leetcode', 'codeforces', or null

                // Build the optional platform context string for the prompts
                $platformContext = "";
                if (!empty($platformName)) {
                    // Capitalize the platform name for better grammar in the prompt
                    $capitalizedPlatformName = ucfirst($platformName);
                    $platformContext = " on the {$capitalizedPlatformName} platform";
                }


                // Optional: Log received data and determined context
                Log::info('ScrapDesctitle: AJAX request received.', [
                    'id' => $id,
                    'platform_name' => $platformName, // Log the name received
                    'request_method' => $request->method(),
                    'request_url' => $request->fullUrl()
                ]);


                // --- MODIFIED PROMPTS to include specific platform context using the extracted name ---
                // Include the determined platform context in both prompts
                // Example: "Just return me the title... for the problem no: 123 on the LeetCode platform and absolutely nothing else"
                $titlePrompt = "Just return me the title in a single sentence for the problem no: {$id}{$platformContext} and absolutely nothing else";

                // Example: "Just return me the description... for the problem no: 123 on the Codeforces platform and absolutely nothing else"
                $descriptionPrompt = "Just return me the description in a single sentence for the problem no: {$id}{$platformContext} and absolutely nothing else";
                $titleResponse = $this->geminiService->generateText($titlePrompt);
                $descriptionResponse = $this->geminiService->generateText($descriptionPrompt);


                $titleToReturn = $titleResponse ?? 'Title Not found'; // Use null coalescing operator
                $descriptionToReturn = $descriptionResponse ?? 'Description Not found';


                // Optional: Log response data before sending
                 Log::info('ScrapDesctitle: Sending response data.', [
                     'title' => $titleToReturn,
                     'description' => $descriptionToReturn
                 ]);


                // Return a JSON response with the fetched data
                return response()->json([
                    'title' => $titleToReturn,
                    'description' => $descriptionToReturn,
                ]);

            } else {
                 // Return a 400 response if the request is not AJAX
                 return response('Request must be AJAX.', 400);
            }

        } catch (\Exception $e) {
            // Log the backend error details including the exception and request data
            Log::error('ScrapDesctitle: Exception caught.', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(), // Include stack trace
                'request_data' => $request->all(), // Log the request data that caused the error
            ]);

            // Return a 500 JSON response with the error message
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}