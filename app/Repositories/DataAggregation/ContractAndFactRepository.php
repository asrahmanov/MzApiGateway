<?php

namespace App\Repositories\DataAggregation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContractAndFactRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DATA_AGGREGATION') . "/api/data-aggregation-contract-and-fact", $request->all());
        return $response->json();
    }

    public function edit(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DATA_AGGREGATION') . "/api/data-aggregation-contract-and-fact", $request->all());
        return $response->json();
    }


    public function list(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-contract-and-fact");
        return $response->json();
    }



    public function getById($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-contract-and-fact/get-by-id/{$id}}");
        return $response->json();
    }

    public function getByCompanyName($name){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_DATA_AGGREGATION')."/api/data-aggregation-contract-and-fact/get-by-name/{$name}");
        return $response->json();
    }




}
