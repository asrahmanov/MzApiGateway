<?php

namespace App\Repositories\DataAggregation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BudgetRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DATA_AGGREGATION') . "/api/data-aggregation-budget", $request->all());
        return $response->json();
    }

    public function edit(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DATA_AGGREGATION') . "/api/data-aggregation-budget", $request->all());
        return $response->json();
    }


    public function list(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-budget");
        return $response->json();
    }


    public function getById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-budget/get-by-id/{$id}}");
        return $response->json();
    }






}
