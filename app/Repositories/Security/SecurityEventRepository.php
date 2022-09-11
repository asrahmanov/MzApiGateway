<?php

namespace App\Repositories\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SecurityEventRepository
{
    public function insert(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_SECURITY') . "/api/security-event", [
            'parent_id' => $request->parent_id,
            'name' => $request->name,

        ]);
        return $response->json();
    }

    public function list(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->get(config('api.API_SECURITY')."/api/security-event");
        return $response->json();
    }







}
