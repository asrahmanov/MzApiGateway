<?php

namespace App\Repositories\HR;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HrReportCovidRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_HR') . "/api/hr-report-covid", [
            'n_1' => $request->n_1,
            'n_2' => $request->n_2,
            'n_3' => $request->n_3,
            'n_4' => $request->n_4,
            'n_5' => $request->n_5,
            'n_6' => $request->n_6,
            'n_7' => $request->n_7,
            'n_8' => $request->n_8,
            'report_day' => $request->report_day,
            'company_id' => $request->company_id
        ]);
        return $response->json();
    }

    public function list(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_HR')."/api/hr-report-covid");
        return $response->json();
    }


    public function getReportByReportDayandCopmanyID($report_day,$copmany_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_HR')."/api/hr-report-covid/get-by-report-day/{$report_day}/{$copmany_id}");
        return $response->json();
    }


}
