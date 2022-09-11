<?php

namespace App\Repositories\Production;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductionReportRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_PRODUCTION') . "/api/production-report", [
            'user_id' => $request->user_id,
            'company_id' => $request->company_id,
            'report_day' => $request->report_day,
            'launch_plan' => $request->launch_plan,
            'launch_fact' => $request->launch_fact,
            'fact_of_transfer_to_otk' => $request->fact_of_transfer_to_otk,
            'fact_of_transfer_to_warehouse' => $request->fact_of_transfer_to_warehouse,
            'launch_previously' => $request->launch_previously,
            'plan_of_transfer_to_otk' => $request->plan_of_transfer_to_otk,
            'launch_plan_ssz' => $request->launch_plan_ssz,
            'sampling' => $request->sampling,
        ]);
        return $response->json();
    }

    public function list(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_PRODUCTION')."/api/production-report");
        return $response->json();
    }


    public function getReportByReportDayandCopmanyID($report_day,$copmany_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_PRODUCTION')."/api/production-report/get-by-report-day/{$report_day}/{$copmany_id}");
        return $response->json();
    }

    public function getByReportBetween($report_day_from,$report_day_to, $copmany_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_PRODUCTION')."/api/production-report/get-by-report-between/{$report_day_from}/$report_day_to/{$copmany_id}");
        return $response->json();
    }


}
