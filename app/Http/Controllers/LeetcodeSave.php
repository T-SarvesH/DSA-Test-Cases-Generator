<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Leetcode_Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LeetcodeSave extends Controller
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

        $newProblem = new Leetcode_Model();

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
        $problems = Leetcode_Model::all();
        return view('Leetcode_saved_TC', compact('problems'));
    }
}