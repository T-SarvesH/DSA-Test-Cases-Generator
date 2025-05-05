<?php

namespace App\Http\Controllers;

use App\Models\Codeforces_Model;
use Illuminate\Http\Request;

class CodeforcesSave extends Controller
{
    public function storeTestCases(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|unique:leetcode_test_cases,Question Id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'constraints' => 'required|string',
            'followUps' => 'nullable|string',
            'EdgeCases' => 'required|string',
            'NormalCases' => 'required|string',
        ]);

        $newProblem = new Codeforces_Model();

        $newProblem->setAttribute('Question Id', $request->input('id'));
        $newProblem->setAttribute('Question Title', $request->input('title'));
        $newProblem->setAttribute('Question Description', $request->input('description'));
        $newProblem->setAttribute('Constraints', $request->input('constraints'));
        $newProblem->setAttribute('Follow Ups', $request->input('followUps'));
        $newProblem->setAttribute('Edge Test Cases', $request->input('EdgeCases'));
        $newProblem->setAttribute('Normal Test Cases', $request->input('NormalCases'));

        $newProblem->save();

        return "Test case saved successfully!";
    }

    public function displayTestCases(Request $request)
    {
        $problems = Codeforces_Model::all();
        return view('Codeforces_saved_TC', compact('problems'));
    }
}
