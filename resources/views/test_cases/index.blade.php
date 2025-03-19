@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">DSA Test Cases</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Problem Title</th>
                <th>Description</th>
                <th>Input</th>
                <th>Expected Output</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testCases as $testCase)
                <tr>
                    <td>{{ $testCase->problem_title }}</td>
                    <td>{{ $testCase->description }}</td>
                    <td><pre>{{ $testCase->input }}</pre></td>
                    <td><pre>{{ $testCase->expected_output }}</pre></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
