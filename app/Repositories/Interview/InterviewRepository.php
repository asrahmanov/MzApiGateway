<?php

namespace App\Repositories\Interview;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InterviewRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_INTERVIEW') . "/api/interview-worksheets", $request->all());
        return $response->json();
    }


    public function edit(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_INTERVIEW') . "/api/interview-worksheets", $request->all());
        return $response->json();
    }


    public function getReportAll(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-worksheets");
        return $response->json();
    }


    public function getByUserId($user_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-worksheets/get-by-userId/{$user_id}}");
        return $response->json();
    }


    public function getById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-worksheets/get-by-id/{$id}}");
        return $response->json();
    }

    public function viewReportById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-worksheets/get-by-id/{$id}}");
        return $response->json();
    }


}
