<!DOCTYPE html>
<html>
<head>
    <title>Codeforces Test Case Generator</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Link your CSS files here if you have any --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
</head>
<body>
    <h1>Codeforces Test Case Generator</h1> <br>
    <br>
    <br>

    <form id="problemForm" action="{{route('save.C.test_cases')}}" method="post"> {{-- Give the form an ID --}}
        @csrf {{-- <<< ADDED: This directive is crucial for CSRF protection --}}

        <fieldset> {{-- Using fieldset for semantic grouping --}}
            <legend>Problem Details</legend>

            <h4> Question Id </h4>
            {{-- Added type="text" as textarea might not be ideal for just an ID --}}
            <input type="text" name="id" placeholder="Question id. Example: 1 for Two Sum...." id="questionId"><br>

            <br><br>
            <h4> Question Title</h4>
            <textarea name="title" id="titleArea" rows="2" cols="50" readonly></textarea><br> {{-- Added readonly as this is output --}}

            <br><br>
            <h4> Description</h4>
            <textarea name="description" id="descriptionArea" rows="8" cols="50" readonly></textarea><br> {{-- Added readonly, increased rows --}}

        </fieldset>

        <br>

        {{-- Added fieldset for test case inputs --}}
        <fieldset>
             <legend>Test Case Inputs</legend>
            <h4> Constraints</h4>
            {{-- User will type constraints here, separated by '.' as requested --}}
            <textarea name="constraints" id="constraintsArea" rows="4" cols="50" placeholder="List constraints, separated by a '.'"></textarea><br>

            <br><br>
            <h4> Follow ups (If not, leave blank)</h4>
            {{-- Ensure this has the correct ID --}}
            <textarea name="followUps" id="followUpsArea" rows="4" cols="50" placeholder="List follow-ups, separated by a '.'"></textarea><br>
        </fieldset>

        <br>

        {{-- Button to trigger test case generation --}}
        {{-- Use type="button" to prevent default form submission --}}
        <button type="button" id="generateTestCasesBtn">Generate Test Cases</button>

        <br><br>

        {{-- Added fieldset for test case outputs --}}
         <fieldset>
             <legend>Generated Test Cases</legend>
            <h4> Edge Cases</h4>
            <textarea name="EdgeCases" id="EdgecasesArea" rows="8" cols="50" readonly></textarea><br> {{-- Added readonly --}}

            <br>
            <h4> Normal cases</h4>
            <textarea name="NormalCases" id="NormalCasesArea" rows="8" cols="50" readonly></textarea><br> {{-- Added readonly --}}
         </fieldset>

        <br>
        <button type="submit" id="submitBtnArea", name="SubmitButton">Submit</button> {{-- Submit button for the form --}}
    </form>
    {{-- Link your JavaScript file --}}
    <script src="{{ asset('js/gen_description.js') }}"></script>
    {{-- If using Vite in Laravel 9+, use @vite('resources/js/app.js') or similar --}}
</body>
</html>