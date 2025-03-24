<!DOCTYPE html>
<html>
<head>
    <title>Codeforces Test Case Generator</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Codeforces Test Case Generator</h1> <br>
    <br>
    <br>

    <form>
        <h4> Question Id </h4>
        <textarea name="id" rows="4" cols="50" placeholder="Question id. Example: 1 for Two Sum...." id="questionId"></textarea><br>

        <br><br>
        <h4> Question Title</h4>
        <textarea name="title" id="titleArea" rows="2" cols="50"></textarea><br>

        <br><br>
        <h4> Description</h4>
        <textarea name="description" id="descriptionArea" rows="4" cols="50"></textarea><br>

        <br><br>
        <h4> Constraints</h4>
        <textarea name="constraints" id="constraintsArea" rows="4" cols="50"></textarea><br>

        <br><br>
        <h4> Edge Cases</h4>
        <textarea name="EdgeCases" id="EdgecasesArea" rows="4" cols="50"></textarea><br>

        <br>
        <h4> Normal cases</h4>
        <textarea name="NormalCases" id="NormalCasesArea" rows="4" cols="50"></textarea><br>

    </form>
    <script src="{{ asset('js/gen_description.js') }}"></script>
</body>
</html>