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

                // Determine the full platform name for the prompt based on the extracted name
                $platformNameFull = null;
                 if ($platformName === 'leetcode') { // Check for 'leetcode'
                     $platformNameFull = 'LeetCode';
                 } elseif ($platformName === 'codeforces') { // Check for 'codeforces'
                     $platformNameFull = 'Codeforces';
                 }


                // Build the optional platform context string for the prompts
                $platformContext = $platformNameFull ? " on the {$platformNameFull} platform" : "";


                // Optional: Log received data and determined context
                Log::info('ScrapDesctitle: AJAX request received.', [
                    'id' => $id,
                    'platform_name_sent' => $platformName, // Log the name received from JS
                    'determined_platform_full' => $platformNameFull ?? 'None', // Log the determined full name
                    'request_method' => $request->method(),
                    'request_url' => $request->fullUrl()
                ]);


                // --- Prompts for Title and Description ---
                // Include the determined platform context in both prompts
                $titlePrompt = "Just return me the title in a single sentence for the problem no: {$id}{$platformContext} and absolutely nothing else";

                $descriptionPrompt = "Just return me the description in a single sentence for the problem no: {$id}{$platformContext} and absolutely nothing else";
                // --- End Prompts ---


                // Call the service to get title and description using the ID
                // Service should return string or null on failure/empty response
                $titleResponse = $this->geminiService->generateText($titlePrompt);
                $descriptionResponse = $this->geminiService->generateText($descriptionPrompt);


                // Prepare data for JSON response
                // If generateText returns null (due to API issues or empty clean response),
                // use "Title Not found" / "Description Not found".
                // The frontend JS will then check for these specific strings.
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

    /**
     * Handles AJAX request to generate test cases using GeminiService.
     * Expects problem details, constraints, and follow-ups in the request body.
     * Requests plain text output from AI and parses it.
     *
     * @param \Illuminate\Http\Request $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateTestCases(Request $request)
    {
        try {
            if ($request->ajax()) {
                // --- Validate inputs for test case generation ---
                $request->validate([
                    'id' => 'required|string', // Problem ID
                    'title' => 'required|string', // Problem Title (should be fetched first)
                    'description' => 'required|string', // Problem Description (should be fetched first)
                    'constraints' => 'nullable|string', // User input constraints separated by '.'
                    'followUps' => 'nullable|string', // User input follow-ups separated by '.'
                     // Validate platformName: must be nullable string, and if present, must be 'leetcode' or 'codeforces'
                    'platformName' => 'nullable|string|in:leetcode,codeforces', // Platform name from frontend
                ]);
                // --- End Validation ---

                // --- Retrieve inputs ---
                $id = $request->input('id');
                $title = $request->input('title');
                $description = $request->input('description');
                $constraints = $request->input('constraints');
                $followUps = $request->input('followUps');
                $platformName = $request->input('platformName'); // 'leetcode', 'codeforces', or null
                // --- End Retrieve inputs ---

                // Determine the full platform name for the prompt
                $platformNameFull = null;
                 if ($platformName === 'leetcode') {
                     $platformNameFull = 'LeetCode';
                 } elseif ($platformName === 'codeforces') {
                     $platformNameFull = 'Codeforces';
                 }
                $platformContext = $platformNameFull ? " on the {$platformNameFull} platform" : "";


                // --- Process Constraints and Follow-ups ---
                $constraintList = [];
                if (!empty($constraints)) {
                    // Split by dot, trim whitespace, and filter out empty strings
                    $constraintList = array_filter(array_map('trim', explode('.', $constraints)), 'strlen');
                }

                 // Split follow-ups by '.'
                $followUpList = [];
                 if (!empty($followUps)) {
                    // Split by dot, trim whitespace, and filter out empty strings
                    $followUpList = array_filter(array_map('trim', explode('.', $followUps)), 'strlen');
                 }

                // Format lists for the prompt
                $constraintsString = !empty($constraintList) ? "Constraints: " . implode("; ", $constraintList) : "No specific constraints provided."; // Use semicolon for clarity
                $followUpsString = !empty($followUpList) ? "Follow Ups: " . implode("; ", $followUpList) : "No specific follow-ups provided."; // Use semicolon for clarity
                // --- End Process Constraints and Follow-ups ---


                // Optional: Log received data for debugging
                Log::info('GeminiController: Test Case Generation request received.', [
                    'id' => $id,
                    'platform_name' => $platformNameFull ?? 'None',
                    'constraints_list' => $constraintList, // Log as lists
                    'followUps_list' => $followUpList,     // Log as lists
                    'request_method' => $request->method(),
                    'request_url' => $request->fullUrl()
                ]);


                // --- Construct Prompt for Test Cases (PLAIN TEXT) ---
                // Design a prompt that asks for edge and normal cases separately and well-formatted.
                // Asking for specific markers to help parsing.
                $testCasePrompt = "Generate a list of edge test cases and normal test cases for the following problem:\n\n"
                                . "Problem ID: {$id}{$platformContext}\n"
                                . "Title: {$title}\n"
                                . "Description: {$description}\n"
                                . "{$constraintsString}\n" // Include formatted constraints
                                . "{$followUpsString}\n\n" // Include formatted follow-ups
                                . "Provide the output test cases below in a clear, well-formatted text format.\n\n"
                                . "Start the edge cases section with exactly 'EDGE CASES:'.\n" // Use clear markers
                                . "Start the normal cases section with exactly 'NORMAL CASES:'.\n" // Use clear markers
                                . "For each test case end with a ',' char and each test case should be on a separate lines.\n"
                                . "Separate test cases with a blank line.\n"
                                . "Do not include any other text before 'EDGE CASES:' or after the last normal case output."
                                . "The test cases should be in a 65: 35 ratio (70% edge cases and rest normal cases)"
                                ."Generate test cases according to range given in the constraints and also generate test cases having a large size as well"
                                . "Dont repeat any test cases and generate a total of around 100 test cases"
                                . "If there are multiple inputs in a test case seperate them with the keyword 'and' , if not dont use the keyword 'and'"
                                . "Dont give under any circumstances the expected output for any test case";
                
                // --- End MODIFIED Prompt ---


                // Choose which prompt to use (plain text now)
                $promptToSend = $testCasePrompt;


                // --- Call the service to generate test cases ---
                // Reuse generateText, expecting it to return the raw API response string
                $rawTestCasesResponse = $this->geminiService->generateText($promptToSend);


                // --- MODIFIED: Parse the raw text response to extract edge and normal cases ---
                $edgeCasesOutput = 'Could not parse Edge Cases from response.';
                $normalCasesOutput = 'Could not parse Normal Cases from response.';

                if (!empty($rawTestCasesResponse)) {
                    // --- Crucial Text Parsing Logic ---
                    // Find the positions of the markers we asked the AI to use
                    $edgeMarker = 'EDGE CASES:';
                    $normalMarker = 'NORMAL CASES:';

                    // --- Replaced Str::indexOf with strpos ---
                    $edgeStartPos = strpos($rawTestCasesResponse, $edgeMarker);
                    $normalStartPos = strpos($rawTestCasesResponse, $normalMarker);
                    // --- End Replacement ---


                    if ($edgeStartPos !== false) {
                        // Edge Cases section marker found
                        // + strlen($edgeMarker) gets the position right AFTER the marker
                        $edgeCasesContentStart = $edgeStartPos + strlen($edgeMarker);

                        if ($normalStartPos !== false && $normalStartPos > $edgeCasesContentStart) {
                            // Both markers found, and normal comes AFTER edge
                            // Extract text between the end of the edge marker and the start of the normal marker
                            $edgeCasesRaw = substr($rawTestCasesResponse, $edgeCasesContentStart, $normalStartPos - $edgeCasesContentStart);
                            $edgeCasesOutput = trim($edgeCasesRaw);

                            // Extract text after the normal marker for normal cases
                            $normalCasesRaw = substr($rawTestCasesResponse, $normalStartPos + strlen($normalMarker));
                            $normalCasesOutput = trim($normalCasesRaw);

                        } else {
                            // Edge marker found, but normal marker is missing or before edge marker.
                            // Assume all content after the edge marker is intended for edge cases.
                             Log::warning('GeminiController: Found EDGE CASES marker but not NORMAL CASES or in wrong order.', ['raw_response' => $rawTestCasesResponse]);
                            $edgeCasesRaw = substr($rawTestCasesResponse, $edgeCasesContentStart);
                            $edgeCasesOutput = trim($edgeCasesRaw) . "\n\n--- WARNING: Could not find 'NORMAL CASES:' marker. ---"; // Indicate potential issue in output
                            $normalCasesOutput = "Could not find 'NORMAL CASES:' marker to separate sections."; // Indicate parsing issue
                        }

                    } else {
                        // Edge marker not found at all
                         Log::warning('GeminiController: Could not find EDGE CASES marker in response.', ['raw_response' => $rawTestCasesResponse]);
                         $edgeCasesOutput = "Could not find 'EDGE CASES:' marker in response. Raw response:\n" . $rawTestCasesResponse; // Show raw response for debugging
                         $normalCasesOutput = 'N/A (Parsing failed)';
                    }

                    // Basic fallback if parsing resulted in empty strings but raw response wasn't empty
                    if (empty($edgeCasesOutput) && empty($normalCasesOutput) && !empty($rawTestCasesResponse)) {
                        // Avoid overwriting the specific 'marker not found' messages unless the raw response was truly empty or contained only markers
                         if (!Str::contains($edgeCasesOutput, 'Could not find') && !Str::contains($normalCasesOutput, 'Could not find') && trim(str_replace([$edgeMarker, $normalMarker], '', $rawTestCasesResponse)) !== '') {
                            $edgeCasesOutput = "Parsing logic failed to extract cases. Raw response:\n" . $rawTestCasesResponse;
                            $normalCasesOutput = 'N/A (Parsing failed)';
                         } else if (trim(str_replace([$edgeMarker, $normalMarker], '', $rawTestCasesResponse)) === '') {
                             // If raw response was only markers and whitespace, indicate no cases were generated
                             $edgeCasesOutput = 'AI generated only markers, no test cases.';
                             $normalCasesOutput = 'AI generated only markers, no test cases.';
                         }
                    }


                } else {
                    // Handle case where API call returned empty response string
                     $edgeCasesOutput = 'Service returned empty response string for test cases.';
                     $normalCasesOutput = 'Service returned empty response string for test cases.';
                }

                // Ensure variables are strings
                $edgeCasesOutput = (string) $edgeCasesOutput;
                $normalCasesOutput = (string) $normalCasesOutput;

                // --- End Parse Response ---


                // --- Return JSON response with structured test cases ---
                // Return the extracted plain text sections in the expected JSON structure
                return response()->json([
                    'edgeCases' => $edgeCasesOutput, // Send extracted edge cases string
                    'normalCases' => $normalCasesOutput, // Send extracted normal cases string
                ]);
                // --- End RETURN ---

            } else {
                // Handle non-AJAX requests
                return response('Request must be AJAX.', 400);
            }

        } catch (\Exception $e) {
            // Log the backend error details
            Log::error('GeminiController: Test Case Generation Exception caught.', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            // Return a 500 JSON response with the error message in the test case fields
            return response()->json([
                'edgeCases' => 'Error generating test cases: ' . $e->getMessage(),
                'normalCases' => 'See Edge Cases field for the primary error.',
                'error' => 'Server error generating test cases: ' . $e->getMessage()
            ], 500);
        }
    }
}