<?php

namespace App\Http\Controllers;

use App\Models\TestCase;
use Illuminate\Http\Request;

class TestCaseController extends Controller
{
    public function index()
    {
        $testCases = TestCase::paginate(10);
        return view('test_cases.index', compact('testCases'));
    }
}
