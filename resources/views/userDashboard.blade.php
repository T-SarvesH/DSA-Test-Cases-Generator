<x-app-layout>
    @section('content')
    @php
    // Practice Info is kept as a placeholder as per your request
    $practiceInfo = [
         'topicsInfo' => [
            ['topic' => 'Array', 'count' => 350],
            ['topic' => 'String', 'count' => 280],
            ['topic' => 'Trie', 'count' => 50],
            ['topic' => 'Sorting', 'count' => 100],
            ['topic' => 'Greedy', 'count' => 100],
            ['topic' => 'Binary Search', 'count' => 125],
         ],
    ];

    // A small change 
    // These now correctly pull from $reqdData coming from your controller
    $easySolved = $reqdData['questionsInfo']['easySolved'] ?? 0;
    $mediumSolved = $reqdData['questionsInfo']['mediumSolved'] ?? 0;
    $hardSolved = $reqdData['questionsInfo']['hardSolved'] ?? 0;
    $totalSolved = $easySolved + $mediumSolved + $hardSolved;

    // --- UPDATED CHART COLORS FOR AESTHETICS ---
    $solvedChartData = [
        'labels' => ['Easy', 'Medium', 'Hard'],
        'datasets' => [[
            'label' => 'Problems Solved',
            'data' => [$easySolved, $mediumSolved, $hardSolved],
            'backgroundColor' => [
                'rgba(52, 211, 153, 0.9)',   // Tailwind Emerald-400
                'rgba(251, 191, 36, 0.9)',   // Tailwind Amber-400
                'rgba(239, 68, 68, 0.9)',    // Tailwind Red-500
            ],
            'borderColor' => [
                'rgba(16, 185, 129, 1)',     // Tailwind Emerald-500
                'rgba(245, 158, 11, 1)',     // Tailwind Amber-500
                'rgba(220, 38, 38, 1)',      // Tailwind Red-600
            ],
            'borderWidth' => 2
        ]]
    ];

    // Topic labels and counts now correctly pull from the hardcoded $practiceInfo
    $topicLabels = [];
    $topicCounts = [];
    foreach ($practiceInfo['topicsInfo'] as $topicData) {
        $topicLabels[] = $topicData['topic'];
        $topicCounts[] = $topicData['count'];
    }

    // --- DIVERSE & VIBRANT PALETTE FOR TOPICS CHART ---
    $topicChartColors = [
        'rgba(59, 130, 246, 0.8)',   // Blue-500
        'rgba(168, 85, 247, 0.8)',   // Purple-500
        'rgba(20, 184, 166, 0.8)',   // Teal-500
        'rgba(249, 115, 22, 0.8)',   // Orange-500
        'rgba(99, 102, 241, 0.8)',   // Indigo-500
        'rgba(236, 72, 153, 0.8)',   // Pink-500
        'rgba(52, 211, 153, 0.8)',   // Emerald-400
        'rgba(239, 68, 68, 0.8)',    // Red-500
    ];

    $topicChartData = [
        'labels' => $topicLabels,
        'datasets' => [[
            'label' => 'Problems Practiced',
            'data' => $topicCounts,
             'backgroundColor' => array_map(function($index) use ($topicChartColors) {
                 return $topicChartColors[$index % count($topicChartColors)];
             }, array_keys($topicLabels)),
             'borderColor' => array_map(function($index) use ($topicChartColors) {
                  $color = $topicChartColors[$index % count($topicChartColors)];
                  if (strpos($color, 'rgba') === 0) {
                      $parts = explode(',', $color);
                      $r = (int)trim($parts[0], 'rgba(');
                      $g = (int)trim($parts[1]);
                      $b = (int)trim($parts[2]);
                      return 'rgba(' . ($r * 0.8) . ',' . ($g * 0.8) . ',' . ($b * 0.8) . ',' . trim($parts[3]);
                  }
                 return $color;
             }, array_keys($topicLabels)),
             'borderWidth' => 1,
        ]]
    ];

    @endphp

    {{-- Loading Overlay --}}
    <div id="loading-overlay" class="fixed inset-0 bg-white/80 dark:bg-gray-900/80 flex items-center justify-center flex-col z-[9999]">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-300 dark:border-gray-700 border-t-blue-500 mb-4"></div>
        <p class="text-gray-700 dark:text-gray-300 text-lg">Loading data...</p>
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-screen-xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

                <div class="flex flex-col gap-8">

                    <div class="bg-white shadow-lg rounded-xl p-8 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl">
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2 inline-block">LeetCode Profile</h2>
                        <div class="flex items-center space-x-8">
                            <img src="{{$reqdData['userDetails']['avatarUrl'] ?? 'https://via.placeholder.com/128/3B82F6/FFFFFF?text=User+Pic'}}" alt="User Profile Picture"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-blue-600 shadow-xl flex-shrink-0">

                            <div>
                                <p class="text-4xl font-extrabold text-gray-800 mb-1">{{$reqdData['userDetails']['uname'] ?? 'N/A'}}</p>
                                <p class="text-xl text-gray-600">Global Rank: <span class="font-bold text-blue-700">{{number_format((int)$reqdData['userDetails']['ranking'] ?? 0)}}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-lg rounded-xl p-6 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl">
                        <h2 class="text-2xl font-bold text-gray-900 mb-5 border-b-2 border-green-500 pb-2 inline-block">Daily LeetCode Question</h2>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ $reqdData['dailyQn']['title'] ?? 'N/A' }}</h3>
                        {{-- Daily Qn acceptance rate was removed from your backend logic; if you add it back, uncomment this --}}
                        {{-- <p class="text-gray-700 mb-3 text-lg">Acceptance Rate: <span class="font-bold text-green-600">{{ $reqdData['dailyQn']['acceptanceRate'] ?? 'N/A' }}</span></p> --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                             @forelse($reqdData['dailyQn']['topics'] ?? [] as $topic)
                                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full shadow-sm">
                                    {{ $topic }}
                                </span>
                             @empty
                                 <span class="text-gray-600 text-sm">No topics available</span>
                             @endforelse
                        </div>

                        <a href="{{ $reqdData['dailyQn']['link'] ?? '#' }}"
                           class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-150 ease-in-out text-lg">
                            Solve This Problem
                        </a>
                    </div>

                </div>


                <div class="flex flex-col gap-8">

                    <div class="bg-white shadow-lg rounded-xl p-6 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl">
                         <h2 class="text-2xl font-bold text-gray-900 mb-5 border-b-2 border-red-500 pb-2 inline-block">Total Solved by Difficulty</h2>
                         <div class="relative w-full h-80 flex items-center justify-center">
                            <canvas id="solvedProblemsChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white shadow-lg rounded-xl p-6 transform transition-all duration-300 hover:scale-[1.005] hover:shadow-2xl">
                         <h2 class="text-2xl font-bold text-gray-900 mb-5 border-b-2 border-purple-500 pb-2 inline-block">Most Practiced Topics</h2>
                         <div class="w-full h-80">
                            <canvas id="topicsChart"></canvas>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    <script>
        window.solvedChartData = @json($solvedChartData);
        window.totalSolved = {{$totalSolved}} ;
        window.topicChartData = @json($topicChartData);

        // Hide the loading overlay once the page content is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        });
    </script>

    @vite('resources/js/dashboard.js')

    @endsection
</x-app-layout>