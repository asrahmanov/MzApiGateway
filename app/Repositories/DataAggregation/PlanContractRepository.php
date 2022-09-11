<?php

namespace App\Repositories\DataAggregation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlanContractRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DATA_AGGREGATION') . "/api/data-aggregation-plan-contract", $request->all());
        return $response->json();
    }

    public function edit(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DATA_AGGREGATION') . "/api/data-aggregation-plan-contract", $request->all());
        return $response->json();
    }


    public function list(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-plan-contract");
        return $response->json();
    }



    public function getById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-plan-contract/get-by-id/{$id}}");
        return $response->json();
    }

    public function getByCompanyName($name){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-plan-contract/get-by-name/{$name}");
        return $response->json();
    }


    public function getGroup(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-plan-contract/get-group");
        return $response->json();
    }




}
