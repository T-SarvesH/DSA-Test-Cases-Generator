<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem List</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            /* CSS to handle text wrapping */
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal; /* Default, but ensures wrapping unless overridden */
            vertical-align: top; /* Align text to the top in cells */
        }
         /* Style for <pre> tags within table cells */
        td pre {
            margin: 0; /* Remove default margin from pre tags */
            padding: 0; /* Remove default padding */
            white-space: pre-wrap; /* Preserve whitespace and wrap long lines */
            word-wrap: break-word; /* Break long words within pre-wrap */
            /* --- ADDED: Inherit font from parent (<td>) --- */
            font-family: inherit;
            /* --- END ADDED --- */
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pagination { /* Basic styling for pagination if used */
            margin-top: 20px;
            display: flex;
            list-style: none;
            padding: 0;
        }
        .pagination li {
            margin-right: 5px;
        }
        .pagination li a,
        .pagination li span {
            padding: 5px 10px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .pagination li span {
             background-color: #007bff;
             color: white;
             border-color: #007bff;
        }
        .pagination li a:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h1>Saved Problems</h1>

    @if ($problems->isEmpty())
        <p>No problems saved yet.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Constraints</th>
                    <th>Follow Ups</th>
                    <th>Edge Cases</th>
                    <th>Normal Cases</th>
                    {{-- Removed Created At and Actions columns --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($problems as $problem)
                    <tr>
                        <td>{{ $problem->id }}</td>
                        {{-- Access attributes with spaces using {'Attribute Name'} --}}
                        {{-- Keeping limit for compactness where appropriate --}}
                        <td>{{ Str::limit($problem->{'Question Id'}, 20) }}</td>
                        <td>{{ Str::limit($problem->{'Question Title'}, 50) }}</td>
                        {{-- Wrap Description content in <pre> tags --}}
                        <td><pre>{{ $problem->{'Question Description'} }}</pre></td>
                        <td>{{ Str::limit($problem->{'Constraints'}, 50) }}</td>
                        <td>{{ Str::limit($problem->{'Follow Ups'}, 50) }}</td>
                        {{-- Wrap Edge Cases and Normal Cases content in <pre> tags --}}
                         <td><pre>{{ $problem->{'Edge Test Cases'} }}</pre></td>
                        <td><pre>{{ $problem->{'Normal Test Cases'} }}</pre></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>