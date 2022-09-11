<?php

namespace App\Repositories\HR;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class hrReportRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_HR') . "/api/hr-report", [
            'text_1' => $request->text_1,
            'text_2' => $request->text_2,
            'text_3' => $request->text_3,
            'text_4' => $request->text_4,
            'date_1' => $request->date_1,
            'date_2' => $request->date_2,
            'date_3' => $request->date_3,
            'date_4' => $request->date_4,
            'report_day' => $request->report_day,
            'report_type_id' => $request->report_type_id,
            'company_id' => $request->company_id
        ]);
        return $response->json();
    }

    public function list(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_HR')."/api/hr-report");
        return $response->json();
    }


    public function getReportByReportDayandCopmanyID($report_day,$copmany_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_HR')."/api/hr-report/get-by-report-day/{$report_day}/{$copmany_id}");
        return $response->json();
    }


}
