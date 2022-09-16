<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class isAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = session()->get('authorized');
        $response = Http::withHeaders([
        ])->post(config('api.API_AUTH') . "/api/mz-users/checkSession", ['token' => $token]);

        if(($response->json())) {
            // Токен подтвержден
            return $next($request);

        } else {
            $request->session()->flush();
              return Redirect::route('users.auth');
        }



//        $check = $response->json();
//        if(!is_null($check)){
//            $ch = $check['check'];
//            if($ch) {
//                return $next($request);
//            } else {
//

//            }
//        } else {
////            $request->session()->flush();
//            return Redirect::route('users.auth');
//        }

    }
}
