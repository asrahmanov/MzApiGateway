<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class UserRepository
{


    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_AUTH') . "/api/users/add", $request->all());

        return $response->json();
    }


    public function edit(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_AUTH') . "/api/users/edit", $request->all());
        return $response->json();
    }


    public function list(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_AUTH') . "/api/users/list/{$request->company_id}");
        return $response->json();
    }


    public function listDzo(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_AUTH') . "/api/users/listDzo/{$request->company_id}");
        return $response->json();
    }

    public function getOne($user_id)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->get(config('api.API_AUTH') . "/api/users/{$user_id}");
        return $response->json();
    }

    public function getRole()
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->get(config('api.API_AUTH') . "/api/users/getRole");
        return $response->json();
    }


}
