<?php

namespace App\Http\Controllers;

use App\Models\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestCaseController extends Controller
{
    public function index(Request $request)
    {
        // $input_problem_title = 'Reverse a String';
        $input_problem_title = $request->input('query');
        // $testCases = TestCase::paginate(10);
        $testCases = DB::select('select * from test_cases where problem_title is ?', [$input_problem_title]);
        return view('test_cases.index', compact('testCases'));
    }
}
