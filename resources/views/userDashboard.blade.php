{{-- resources/views/userDashboard.blade.php --}}

<x-app-layout>
    @section('content')
    @php
    // Placeholder data structure mirroring potential API responses
    // Replace this entire $dashboardDataPlaceholder array and the logic below
    // with fetching your actual data from the controller
    $dashboardDataPlaceholder = [
        'userDetails' => [
            'uname' => 't1Sr_',
            'ranking' => '10000', // Assuming this is rank, not score based on image
            'avatarUrl' => 'https://preview.redd.it/my-art-of-okarun-v0-mre5in3890ud1.jpeg?width=1080&crop=smart&auto=webp&s=d0965c8bee2eb8dd9dd89d78839bf570f6da1dd6', // Placeholder URL
            // Add other user details here
        ],
        'dailyQuestion' => [
            'title' => 'Two Sum',
            'acceptanceRate' => '55.5%',
            'topics' => ['Array', 'Hash Table', 'Two Pointers'], // Placeholder topics/tags
            // Add URL to the question form if you have one
            'formUrl' => '#', // Placeholder URL
        ],
        'solvedInfo' => [ // Data for Solved Problems Doughnut
            'easySolved' => 197,
            'mediumSolved' => 267,
            'hardSolved' => 20,
        ],
         'topicsInfo' => [ // Data for Topics Histogram
            // Placeholder data for most practiced topics
            ['topic' => 'Array', 'count' => 350],
            ['topic' => 'String', 'count' => 280],
            ['topic' => 'Trie', 'count' => 50],
            ['topic' => 'Sorting', 'count' => 100],
            ['topic' => 'Greedy', 'count' => 100],
            ['topic' => 'Binary Search', 'count' => 125],
            // Add more placeholder topics
         ],
         // --- Placeholder Data for Streak Calendar (Approx. 2 Months, Grouped) ---
         'streakInfo' => [
             'currentStreak' => 7, // Example: 7 consecutive days
             'longestStreak' => 15, // Example: Longest streak ever
             // Data for calendar: Grouped by month, each month contains weeks
             'activityLastTwoMonths' => [
                 'March' => [ // Older month (e.g., March)
                     // Each inner array is a week (7 days)
                     [false, false, true, true, true, false, true],
                     [true, true, true, true, true, false, false],
                     [true, true, true, true, true, true, true],
                     [false, true, true, false, true, true, true],
                 ],
                 'April' => [ // Newer month (e.g., April)
                      [true, true, true, true, true, true, true], // Part of current streak
                      [false, true, true, false, true, true, true],
                      [true, true, true, true, false, true, true],
                      [true, true, true, true, true, true, true], // Part of current streak
                      [true, false, false, true, true, true, true], // Spans into next month if needed
                 ],
                  'May' => [ // Current partial month (e.g., May)
                      [true, true, false, false, false, false, false], // Part of current streak
                  ],
             ],
         ],
    ];

    // Calculate data and pass to JavaScript for charts
    // This logic should also be updated when fetching real data
    $easySolved = $dashboardDataPlaceholder['solvedInfo']['easySolved'] ?? 0;
    $mediumSolved = $dashboardDataPlaceholder['solvedInfo']['mediumSolved'] ?? 0;
    $hardSolved = $dashboardDataPlaceholder['solvedInfo']['hardSolved'] ?? 0;
    $totalSolved = $easySolved + $mediumSolved + $hardSolved; // Calculate total

    // --- Updated Vibrant Colors for Doughnut Chart ---
    $solvedChartData = [
        'labels' => ['Easy', 'Medium', 'Hard'],
        'datasets' => [[
            'label' => 'Problems Solved',
            'data' => [$easySolved, $mediumSolved, $hardSolved],
            'backgroundColor' => [
                'rgba(40, 167, 69, 0.9)', // Vibrant Green (Success)
                'rgba(253, 184, 3, 0.9)', // Vibrant Orange (Warning)
                'rgba(220, 53, 69, 0.9)', // Vibrant Red (Danger)
            ],
            'borderColor' => [ // Use slightly darker or same for border
                'rgba(34, 139, 34, 1)', // Forest Green
                'rgba(255, 140, 0, 1)',  // Dark Orange
                'rgba(178, 34, 34, 1)',  // Fire Brick
            ],
            'borderWidth' => 1
        ]]
    ];

    // Prepare data for Topics Histogram
    $topicLabels = [];
    $topicCounts = [];
    foreach ($dashboardDataPlaceholder['topicsInfo'] as $topicData) {
        $topicLabels[] = $topicData['topic'];
        $topicCounts[] = $topicData['count'];
    }

    // --- Updated Vibrant Colors for Bar Chart ---
    // Using an array of colors for more vibrancy
    $topicChartColors = [
        'rgba(0, 123, 255, 0.9)',  // Vibrant Blue
        'rgba(255, 193, 7, 0.9)',  // Vibrant Yellow
        'rgba(23, 162, 184, 0.9)', // Vibrant Teal
        'rgba(108, 117, 125, 0.9)', // Muted Gray for less practiced
        'rgba(40, 167, 69, 0.9)',  // Vibrant Green
        'rgba(111, 66, 193, 0.9)', // Vibrant Purple
         // Add more colors if you have more topics
    ];


    $topicChartData = [
        'labels' => $topicLabels,
        'datasets' => [[
            'label' => 'Problems Practiced',
            'data' => $topicCounts,
             // Assign colors from the palette, repeating if necessary
             'backgroundColor' => array_map(function($index) use ($topicChartColors) {
                 return $topicChartColors[$index % count($topicChartColors)];
             }, array_keys($topicLabels)),
             'borderColor' => array_map(function($index) use ($topicChartColors) {
                 // Use a slightly darker version for border or just use the same color
                  $color = $topicChartColors[$index % count($topicChartColors)];
                  // Basic logic to make border slightly darker (example: reduce alpha or adjust rgb)
                  if (strpos($color, 'rgba') === 0) {
                      $parts = explode(',', $color);
                      $alpha = (float)trim($parts[3], ' )');
                      $parts[3] = ($alpha * 1.1 > 1 ? 1 : $alpha * 1.1) . ')'; // Increase alpha slightly
                      return implode(',', $parts);
                  }
                 return $color; // Or just return the same color if rgba logic is too complex
             }, array_keys($topicLabels)),
             'borderWidth' => 1,
        ]]
    ];

    // Data for Streak Calendar - now structured by month
    $activityByMonth = $dashboardDataPlaceholder['streakInfo']['activityLastTwoMonths'] ?? [];
    $currentStreak = $dashboardDataPlaceholder['streakInfo']['currentStreak'] ?? 0;
    $longestStreak = $dashboardDataPlaceholder['streakInfo']['longestStreak'] ?? 0;

@endphp
        {{-- Main content container with padding and centering --}}
        {{-- Increased max-width slightly for more space if needed --}}
        <div class="max-w-screen-xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            {{-- --- Two-Column Layout Container --- --}}
            {{-- All cards are now inside this grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12"> {{-- Create a 2-column grid on medium screens and up, with gap --}}

                {{-- --- Card 1: User LeetCode Info (Expanded Horizontally - spans 2 columns) --- --}}
                {{-- Added md:col-span-2 to make it span both columns on medium screens and up --}}
                 <div class="bg-white shadow-md rounded-lg p-6 mb-4 md:col-span-2 hover:shadow-lg transition duration-200 ease-in-out"> {{-- Reduced mb- as gap handles spacing --}}
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">LeetCode Profile</h2>
                    <div class="flex items-center space-x-6"> {{-- Increased space-x --}}
                        {{-- Placeholder for Profile Picture --}}
                        {{-- Updated border color to a vibrant accent --}}
                        <img src="{{$dashboardDataPlaceholder['userDetails']['avatarUrl']}}" alt="User Profile Picture"
                             class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 shadow-lg">

                        <div>
                            {{-- Placeholder for Username and Rank --}}
                            <p class="text-2xl font-bold text-gray-800">{{$dashboardDataPlaceholder['userDetails']['uname']}}</p>
                            <p class="text-lg text-gray-700">Rank: {{number_format($dashboardDataPlaceholder['userDetails']['ranking'])}}</p>
                            {{-- Add other specific user details here if needed --}}
                        </div>
                    </div>
                </div>

                {{-- --- Left Column (Streak and Daily Question) --- --}}
                {{-- This div now represents the left column of the 2nd row onwards --}}
                <div class="flex flex-col gap-8"> {{-- Stack items vertically in the left column with gap --}}

                    {{-- --- Card 2: Streak Calendar (Expanded Sideways) --- --}}
                    {{-- Added subtle hover effect and structure for calendar grid --}}
                     <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition duration-200 ease-in-out">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Coding Streak</h2>

                        <div class="flex items-center justify-between mb-4">
                             {{-- Current Streak --}}
                            <div>
                                <p class="text-gray-600 text-sm">Current Streak</p>
                                <p class="text-2xl font-bold text-green-600">{{ $currentStreak }} Days</p> {{-- Use a vibrant color --}}
                            </div>
                            {{-- Longest Streak --}}
                            <div>
                                <p class="text-gray-600 text-sm text-right">Longest Streak</p>
                                <p class="text-2xl font-bold text-purple-600 text-right">{{ $longestStreak }} Days</p> {{-- Use another vibrant color --}}
                            </div>
                        </div>

                        {{-- Calendar Grid Representation - Expanded Sideways --}}
                        {{-- Main container for horizontal calendar layout with scrolling --}}
                        <div class="flex overflow-x-auto pb-2 gap-0.5 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100"> {{-- Added gap-0.5 here for space between week columns --}}
                             @foreach($activityByMonth as $monthName => $weeks)
                                {{-- Container for Month Label and its weeks --}}
                                <div class="flex flex-col mr-2"> {{-- Added mr-2 for space between month blocks --}}
                                    {{-- Month Label (positioned above weeks) --}}
                                     <h4 class="text-sm font-semibold text-gray-800 mb-1">{{ $monthName }}</h4>
                                    <div class="flex gap-0.5"> {{-- Flex container for weeks (arranged horizontally within the month block) --}}
                                        @foreach($weeks as $week)
                                            {{-- Flex column for each week (7 days) --}}
                                            <div class="flex flex-col gap-0.5">
                                                @foreach($week as $dayActive)
                                                    {{-- Each day box --}}
                                                    <div class="w-4 h-4 rounded-sm
                                                        @if($dayActive)
                                                            bg-green-500 {{-- Vibrant color for active days --}}
                                                        @else
                                                            bg-gray-200 {{-- Muted color for inactive days --}}
                                                        @endif
                                                        " title="{{ $dayActive ? 'Active' : 'Inactive' }}"> {{-- Add tooltip --}}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                             @endforeach
                        </div>
                         {{-- Updated label --}}
                         <p class="text-right text-gray-500 text-xs mt-2">Last {{ count($activityByMonth) }} months (approx.) (Scroll horizontally)</p> {{-- Adjusted label based on data --}}
                    </div>


                    {{-- --- Card 3: Daily LeetCode Question --- --}}
                    {{-- Added subtle hover effect --}}
                     <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition duration-200 ease-in-out">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Daily LeetCode Question</h2>
                        {{-- Placeholder for Question Title --}}
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $dashboardDataPlaceholder['dailyQuestion']['title'] ?? 'N/A' }}</h3> {{-- Use data --}}
                        {{-- Placeholder for Acceptance Rate --}}
                        {{-- Can add color hint based on rate using JS/conditional classes --}}
                        <p class="text-gray-700 mb-2">Acceptance Rate: <span class="font-medium text-green-600">{{ $dashboardDataPlaceholder['dailyQuestion']['acceptanceRate'] ?? 'N/A' }}</span></p> {{-- Use data --}}
                        {{-- Placeholder for Question Type/Tags --}}
                        {{-- Styled topics as tags --}}
                        <div class="flex flex-wrap gap-2 mb-4">
                             @forelse($dashboardDataPlaceholder['dailyQuestion']['topics'] ?? [] as $topic) {{-- Loop through data --}}
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $topic }}
                                </span>
                             @empty
                                 <span class="text-gray-600 text-sm">No topics available</span>
                             @endforelse
                        </div>


                        {{-- Button to go to LC form --}}
                        {{-- Updated button color to a vibrant blue and added hover effect --}}
                        <a href="{{ $dashboardDataPlaceholder['dailyQuestion']['formUrl'] ?? '#' }}"
                           class="inline-block bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                            Solve This Problem
                        </a>
                    </div>

                </div> {{-- --- End Left Column --- --}}


                {{-- --- Right Column (Charts) --- --}}
                {{-- This div now represents the right column of the 2nd row onwards --}}
                <div class="flex flex-col gap-8"> {{-- Stack items vertically in the right column with gap --}}

                    {{-- --- Top Right Card: Solved Problems Doughnut Chart --- --}}
                    {{-- Added subtle hover effect --}}
                    <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition duration-200 ease-in-out">
                         <h2 class="text-xl font-semibold text-gray-900 mb-4">Total Solved by Difficulty</h2>
                         {{-- Container for the Doughnut Chart --}}
                         {{-- Ensured container helps with responsiveness --}}
                         <div class="relative w-full h-80 flex items-center justify-center"> {{-- Added flex for centering --}}
                            <canvas id="solvedProblemsChart"></canvas>
                            {{-- The center total text is handled by JS plugin --}}
                        </div>
                    </div>

                    {{-- --- Bottom Right Card: Most Practiced Topics Histogram --- --}}
                    {{-- Added subtle hover effect --}}
                    <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition duration-200 ease-in-out">
                         <h2 class="text-xl font-semibold text-gray-900 mb-4">Most Practiced Topics</h2>
                         {{-- Container for the Histogram Chart --}}
                         {{-- Ensured container helps with responsiveness --}}
                         <div class="w-full h-80">
                            <canvas id="topicsChart"></canvas> {{-- New canvas for histogram --}}
                        </div>
                    </div>

                </div> {{-- --- End Right Column --- --}}

            </div> {{-- --- End Two-Column Layout --- --}}

        </div> {{-- End max-w-screen-xl mx-auto py-8 px-4 sm:px-6 lg:px-8 --}}

        {{-- --- Prepare Data in PHP (Placeholder Values) --- --}}
        {{-- This block *MUST* be inside @section('content') --}}


        <script>
            console.log("Blade inline script running: About to define chart data");
            window.solvedChartData = @json($solvedChartData);
            window.totalSolved = {{$totalSolved}} ;
            window.topicChartData = @json($topicChartData);
             // No need to pass activity data to JS if rendering calendar in Blade
            console.log("Blade inline script: Data defined on window:", window.solvedChartData, "Total:", window.totalSolved, "Topics:", window.topicChartData);
        </script>

        {{-- Use @vite to include the processed dashboard.js --}}
        {{-- This directive block *MUST* be inside @section('content') --}}
        @vite('resources/js/dashboard.js')

        {{-- --- END OF SECTIONS MOVED INSIDE --- --}}

    @endsection {{-- END OF @section('content') --}}
</x-app-layout>