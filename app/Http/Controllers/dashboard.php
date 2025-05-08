<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Leetcode_Model;

class dashboard extends Controller
{
    protected $url;
    public function fetchUserDetails(){
        
        $this->url = config('services.leetcode.baseUrl');

        try {
            
            $uname = 't1Sr_';
            $response = Http::get($this->url . $uname);
            
            if($response->successful()){

                $data = $response->json();
                return view('userDashboard', ['data' => $data]);
            }

            else
            return view('userDashBoard',['status' => $response->status()]);

        } catch (\Exception $e) {
            // Handle the exception
            return view('userDashboard', ['error'=> $e->getMessage()]);            
        }
        
    }
}
