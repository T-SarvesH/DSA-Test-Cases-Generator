{{-- resources/views/userDashboard.blade.php --}}

<x-app-layout>
    @section('content')
        {{-- Main content container with padding and centering --}}
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">User Dashboard Details</h1>

                {{-- ... (Your other user details display code) ... --}}

                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Solved Problems Chart:</h2>
                    {{-- Add a container div with Tailwind classes for sizing --}}
                    {{-- w-96 (width: 24rem) and h-96 (height: 24rem) make it a medium size --}}
                    {{-- mx-auto centers it horizontally --}}
                    <div class="w-96 h-96 mx-auto">
                        <canvas id="solvedProblemsChart"></canvas>
                    </div>
                </div>

            </div> {{-- End bg-white shadow-md rounded-lg p-6 --}}
        </div> {{-- End max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 --}}

        {{-- Prepare Data in PHP --}}
        @php
            // Use data from your $reqdData['questionsInfo'] if available, with fallbacks
            $easySolved = 197;
            $mediumSolved = 267;
            $hardSolved = 20;
            $totalSolved = $easySolved + $mediumSolved + $hardSolved; // Calculate total

            $chartData = [
                'labels' => ['Easy', 'Medium', 'Hard'],
                'datasets' => [[
                    'label' => 'Problems Solved',
                    'data' => [$easySolved, $mediumSolved, $hardSolved], // Use fetched data
                    'backgroundColor' => [
                        'rgba(132, 204, 22, 0.8)',  // Lime (Tailwind lime-500 with opacity)
                        'rgba(250, 204, 21, 0.8)',  // Yellow (Tailwind yellow-400 with opacity)
                        'rgba(239, 68, 68, 0.8)',   // Red (Tailwind red-500 with opacity)
                    ],
                    'borderColor' => [
                        'rgba(132, 204, 22, 1)',
                        'rgba(250, 204, 21, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 1
                ]]
            ];
        @endphp

        {{-- Define JavaScript variables on the window object --}}
        {{-- This needs to be BEFORE the @vite directive --}}
        <script>
            console.log("Blade inline script running: About to define chart data");
            window.solvedChartData = @json($chartData);
            window.totalSolved = {{ $totalSolved }}; // Pass the total solved count
            console.log("Blade inline script: Data defined on window:", window.solvedChartData, "Total:", window.totalSolved);
        </script>

        {{-- Use @vite to include the processed dashboard.js --}}
        @vite('resources/js/dashboard.js')

    @endsection {{-- END OF @section('content') --}}
</x-app-layout>