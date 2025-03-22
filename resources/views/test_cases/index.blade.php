@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">DSA Test Cases</h2>
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Problem Title</th>
                <th class="border border-gray-300 px-4 py-2">Description</th>
                <th class="border border-gray-300 px-4 py-2">Input</th>
                <th class="border border-gray-300 px-4 py-2">Expected Output</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testCases as $testCase)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $testCase->problem_title }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $testCase->description }}</td>
                    <td class="border border-gray-300 px-4 py-2"><pre>{{ $testCase->input }}</pre></td>
                    <td class="border border-gray-300 px-4 py-2"><pre>{{ $testCase->expected_output }}</pre></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
