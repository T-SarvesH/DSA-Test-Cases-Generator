<!DOCTYPE html>
<html>
<head>
    <title>Gemini AI Text Generator</title>
</head>
<body>
    <h1>Gemini AI Text Generator</h1>
    <form action="{{ route('gemini.generate') }}" method="POST">
    @csrf
    <textarea name="prompt" rows="4" cols="50" placeholder="Enter your prompt..."></textarea><br>
    <button type="submit">Generate Text</button>
</form>

</body>
</html>