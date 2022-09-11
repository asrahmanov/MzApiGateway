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

//        if(!strlen(session('authorized')) == 64) {
//            $request->session()->flush();
//            return Redirect::route('auth.login.form');
//        }
//        return $next($request);



        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH').'/api/auth/checkSession');
        $check = $response->json();
        if(!is_null($check)){
            $ch = $check['check'];
            if($ch) {
                return $next($request);
            } else {
              $request->session()->flush();
              return Redirect::route('auth.login.form');
            }
        } else {
//            $request->session()->flush();
            return Redirect::route('auth.login.form');
        }

    }
}
