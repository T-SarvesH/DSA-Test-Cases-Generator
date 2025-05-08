<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Leetcode_Model;

class dashboard extends Controller
{
    protected $url;
    protected $uname;

    //Constructor to protect the base URL (Good practice in production)
    public function __construct()
    {
        $this->url = config('services.leetcode.baseUrl');
    }
    
    private function questionSolved(){

        try {
            
            $response = Http::get($this->url . $this->uname . 'solved');  
            if($response->successful()){

                $data = $response->json();
                $filteredData = ['totalSolved'=> $data['solvedProblem'],
                'easySolved'=> $data['easySolved'],
                'mediumSolved'=> $data['mediumSolved'],
                'hardSolved'=> $data['hardSolved'],
            ];

                return $filteredData;    
            }

            else
            return $response->status();

        } catch (\Exception $e) {
            
            return $e->getMessage();
        }
        
    }

    //Rest all functions are private as a good Devops practice
    private function fetchUserDetails(){

        try {
            
            $response = Http::get($this->url . $this->uname);  
            if($response->successful()){

                $data = $response->json();
                $filteredData = ['uname' => $this->uname, 'ranking' => $data['ranking'],
                'avatarUrl' => $data['avatar'], 'reputation' => $data['reputation'],
                ];
                return view('userDashboard', ['data' => $data]);    
            }

            else
            return $response->status();

        } catch (\Exception $e) {
            
            return $e->getMessage();
        }
    }
    public function main(){
        
        
        $data = [];
        /*
        $this->uname = 't1Sr_';

        $data['userDetails'] = $this->fetchUserDetails();
        $data['questionsInfo'] = $this->questionSolved();
        */
        return view('userDashboard', ['reqdData' => $data]);
    }
}
