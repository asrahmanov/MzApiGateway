<?php

namespace App\Repositories\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class UserRepository
{


    public function login($login,$password)
    {
        $response = Http::withHeaders([
        ])->get(config('api.API_AUTH') . "/api/mz-users/login/{$login}/{$password}");

        return $response->json();
    }




}
