<?php

namespace App\Repositories\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SecurityReportRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_SECURITY') . "/api/security-report", [
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id
        ]);
        return $response->json();
    }

    public function list(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_SECURITY')."/api/security-report");
        return $response->json();
    }


    public function get_by_report_day($event_day,$copmany_id){

        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_SECURITY')."/api/security-report/get-by-report-day/{$event_day}/{$copmany_id}");
        return $response->json();
    }







}
