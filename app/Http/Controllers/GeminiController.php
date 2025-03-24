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

    public function codeforcedForm(){

        return view('/codeforces/codeforces-form');
    }

    public function scrapDesctitle(Request $request)
{
    try {
        if ($request->ajax()) {
            $request->validate([
                'id' => 'required|string',
            ]);

            $id = $request->input('id');

            $titlePrompt = "Just return me the title in a single sentence for the problem {$id} and absolutely nothing else";
            $titleResponse = $this->geminiService->generateText($titlePrompt);

            $descriptionPrompt = "Just return me the description in a single sentence for the problem {$id} and absolutely nothing else";
            $descriptionResponse = $this->geminiService->generateText($descriptionPrompt);

            // Handle empty responses
            $titleResponse = empty($titleResponse) ? 'Title Not found' : $titleResponse;
            $descriptionResponse = empty($descriptionResponse) ? 'Description Not found' : $descriptionResponse;

            return response()->json([
                'title' => $titleResponse,
                'description' => $descriptionResponse,
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}
}