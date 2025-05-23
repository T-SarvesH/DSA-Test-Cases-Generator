<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Import Log facade for logging
use App\Models\Leetcode_Model; // Assuming you have this model
use App\Models\User;           // Assuming you have this model
use Illuminate\Support\Facades\Auth; // For getting the authenticated user's ID

class Dashboard extends Controller // Class names should typically be PascalCase
{
    protected $url;
    protected $uname;

    public function __construct()
    {
        // Ensure that 'services.leetcode.baseUrl' is correctly configured in config/services.php
        $this->url = config('services.leetcode.baseUrl');
        if (empty($this->url)) {
            Log::error('LeetCode base URL is not configured. Check config/services.php');
            // In a real application, you might redirect or show a critical error here.
            // For now, it will proceed but likely fail if the URL is truly missing.
        }
    }

    private function questionSolved(): array // Explicitly declare return type as array
    {
        try {
            // Added timeout to prevent hanging, and ensured correct slash for typical API endpoints
            $response = Http::timeout(10)->get($this->url . $this->uname . '/solved');

            if ($response->successful()) {
                $data = $response->json();
                $filteredData = [
                    'totalSolved' => $data['solvedProblem'] ?? 0,
                    'easySolved'  => $data['easySolved'] ?? 0,
                    'mediumSolved'=> $data['mediumSolved'] ?? 0,
                    'hardSolved'  => $data['hardSolved'] ?? 0,
                ];
                return $filteredData;
            } else {
                // Log the error and return default array on unsuccessful response
                Log::error('LeetCode solved API failed for user ' . $this->uname . '. Status: ' . $response->status() . '. Body: ' . $response->body());
                return [
                    'totalSolved' => 0,
                    'easySolved'  => 0,
                    'mediumSolved'=> 0,
                    'hardSolved'  => 0,
                ];
            }
        } catch (\Exception $e) {
            // Log the exception and return default array on any error
            Log::error('Exception in questionSolved for user ' . $this->uname . ': ' . $e->getMessage());
            return [
                'totalSolved' => 0,
                'easySolved'  => 0,
                'mediumSolved'=> 0,
                'hardSolved'  => 0,
            ];
        }
    }

    private function fetchUserDetails(): array // Explicitly declare return type as array
    {
        try {
            // Added timeout
            $response = Http::timeout(10)->get($this->url . $this->uname);

            if ($response->successful()) {
                $data = $response->json();
                $filteredData = [
                    'uname'     => $this->uname,
                    'ranking'   => $data['ranking'] ?? 'N/A',
                    'avatarUrl' => $data['avatar'] ?? 'https://via.placeholder.com/128/3B82F6/FFFFFF?text=User+Pic', // Fallback image
                ];
                return $filteredData;
            } else {
                // Log the error and return default array on unsuccessful response
                Log::error('LeetCode user details API failed for user ' . $this->uname . '. Status: ' . $response->status() . '. Body: ' . $response->body());
                return [
                    'uname'     => $this->uname,
                    'ranking'   => 'N/A',
                    'avatarUrl' => 'https://via.placeholder.com/128/3B82F6/FFFFFF?text=Error', // Error image
                ];
            }
        } catch (\Exception $e) {
            // Log the exception and return default array on any error
            Log::error('Exception in fetchUserDetails for user ' . $this->uname . ': ' . $e->getMessage());
            return [
                'uname'     => $this->uname,
                'ranking'   => 'N/A',
                'avatarUrl' => 'https://via.placeholder.com/128/3B82F6/FFFFFF?text=Error',
            ];
        }
    }

    private function getDailyQn(): array // Explicitly declare return type as array
    {
        try {
            // Added timeout
            $response = Http::timeout(10)->get($this->url . 'daily');

            if ($response->successful()) {
                $data = $response->json();
                $filteredData = [
                    'title'  => $data['questionTitle'] ?? 'N/A',
                    'link'   => $data['questionLink'] ?? '#',
                    // 'acceptanceRate' => $data['acceptanceRate'] ?? 'N/A', // Uncomment if API provides this and you want to use it
                ];

                $topicTags = [];
                // Ensure 'topicTags' key exists and is an array before iterating
                if (isset($data['topicTags']) && is_array($data['topicTags'])) {
                    foreach ($data['topicTags'] as $tag) {
                        $topicTags[] = $tag['name'] ?? ''; // Use null coalescing for safety
                    }
                }
                $filteredData['topics'] = $topicTags; // Ensure the key matches Blade's expectation ('topics', not 'topicTags')

                return $filteredData; // This is crucial to return the structured data
            } else {
                // Log the error and return default array on unsuccessful response
                Log::error('LeetCode daily question API failed. Status: ' . $response->status() . '. Body: ' . $response->body());
                return [
                    'title'  => 'Failed to fetch Daily Question',
                    'link'   => '#',
                    'topics' => ['Error'],
                ];
            }
        } catch (\Exception $e) {
            // Log the exception and return default array on any error
            Log::error('Exception in getDailyQn: ' . $e->getMessage());
            return [
                'title'  => 'Error fetching Daily Question',
                'link'   => '#',
                'topics' => ['Error'],
            ];
        }
    }

    public function main(Request $request)
    {
        Log::info('Dashboard main function called'); // Log when the main function is called
        $data = [];

        // Fetch the authenticated user's LeetCode ID
        $this->uname = 't1Sr_';

        // Call the private functions to get data, they are now guaranteed to return arrays
        $data['userDetails'] = $this->fetchUserDetails();
        $data['questionsInfo'] = $this->questionSolved();
        $data['dailyQn'] = $this->getDailyQn();

        // Pass the $data array to the view
        return view('userDashboard', ['reqdData' => $data]);
    }
}