{{-- Wrap the content in your main layout --}}
<x-app-layout>
    {{-- Define the content section as needed by your layout (assuming your layout uses this section) --}}
    @section('content')

    {{-- Add the standard content container div --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8"> {{-- Adjust max-w and padding as per your layout --}}

        {{-- Modified <style> block with fonts reverted but other enhancements kept --}}
        <style>
            body {
                /* Reverted font-family to original sans-serif */
                font-family: sans-serif;
                line-height: 1.7;
                color: #333;
            }
            h1 {
                color: #1a202c;
                font-size: 1.8em;
                margin-bottom: 25px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            }
            th, td {
                border: 1px solid #e2e8f0;
                padding: 12px 10px;
                text-align: left;
                word-wrap: break-word;
                overflow-wrap: break-word;
                white-space: normal;
                vertical-align: top;
                color: #4a5568;
                font-size: 0.95em;
            }
             /* Style for <pre> tags within table cells - Fonts reverted, other styles kept */
            td pre {
                margin: 0;
                /* Reverted font-family to inherit */
                font-family: inherit;
                background-color: #f8f8f8;
                border: 1px solid #e2e8f0;
                border-radius: 4px;
                padding: 12px;
                white-space: pre-wrap;
                word-wrap: break-word;
                font-size: 0.9em;
                line-height: 1.6;
                color: #2d3748;
            }
            th {
                background-color: #edf2f7;
                color: #2d3748;
                font-weight: 600;
                font-size: 0.85em;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            tr:nth-child(even) {
                background-color: #f7fafc;
            }
            tr:hover {
                background-color: #ebf4ff;
            }

            /* Original pagination styling */
            .pagination {
                margin-top: 20px;
                display: flex;
                list-style: none;
                padding: 0;
                justify-content: center;
            }
            .pagination li {
                margin: 0 4px;
            }
            .pagination li a,
            .pagination li span {
                padding: 8px 12px;
                border: 1px solid #cbd5e0;
                text-decoration: none;
                color: #4a5568;
                border-radius: 4px;
                transition: all 0.2s ease-in-out;
            }
            .pagination li span {
                 background-color: #4299e1;
                 color: white;
                 border-color: #4299e1;
            }
            .pagination li a:hover {
                background-color: #edf2f7;
                border-color: #a0aec0;
            }
             .pagination li.disabled span {
                 opacity: 0.5;
             }
        </style>


        {{-- Original body content --}}
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
                            <td>{{ Str::limit($problem->{'Question Id'}, 20) }}</td>
                            <td>{{ Str::limit($problem->{'Question Title'}, 50) }}</td>
                            <td><pre>{{ $problem->{'Question Description'} }}</pre></td>
                            <td>{{ Str::limit($problem->{'Constraints'}, 50) }}</td>
                            <td>{{ Str::limit($problem->{'Follow Ups'}, 50) }}</td>
                             <td><pre>{{ $problem->{'Edge Test Cases'} }}</pre></td>
                            <td><pre>{{ $problem->{'Normal Test Cases'} }}</pre></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif {{-- End @if ($problems->isEmpty()) --}}

    </div> {{-- End Container div --}}

    @endsection {{-- End section content --}}
</x-app-layout>