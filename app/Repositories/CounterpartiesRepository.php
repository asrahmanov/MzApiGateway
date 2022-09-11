<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class CounterpartiesRepository
{



    /*
     *  'inn' => $request->inn,
     *  'name' => $request->name,
     *  'phone' => $request->phone,
     *  'description' => $request->description,
     *
     * */
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_DIRECTORY') . "/api/counterparties/insert", $request->all());
        if ($response->ok() || $response->status() === 201){
            return $response->json();
        }else{
            return['errors'=>$response->status()];
        }
    }


    public function list(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_DIRECTORY')."/api/counterparties/list");
        return $response->json();
    }

    public function checkinn(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_DIRECTORY')."/api/counterparties/checkinn/{$request->inn}");
        return $response->json();
    }





}
