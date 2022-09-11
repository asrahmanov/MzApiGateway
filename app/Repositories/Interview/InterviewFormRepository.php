<?php

namespace App\Repositories\Interview;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InterviewFormRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_INTERVIEW') . "/api/interview-form", $request->all());
        return $response->json();
    }


    public function edit(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_INTERVIEW') . "/api/interview-form", $request->all());
        return $response->json();
    }

    public function delete($id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->delete(config('api.API_INTERVIEW') . "/api/interview-form/{$id}");
        return $response->json();
    }


    public function getReportAll(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-form");
        return $response->json();
    }


    public function getByUserId($user_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-form/get-by-userId/{$user_id}}");
        return $response->json();
    }


    public function getById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-form/get-by-id/{$id}");
        return $response->json();
    }

    public function viewReportById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_INTERVIEW')."/api/interview-form/get-by-id/{$id}}");
        return $response->json();
    }


}
