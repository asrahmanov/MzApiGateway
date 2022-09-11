<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

//    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Http\RedirectResponse
     */


        public function login(Request $request)
        {

            $response = Http::post(config('api.API_AUTH') . '/api/auth/login', ['email' => $request->email, 'password' => $request->password]);
            if ($response->ok()) {
                session()->put('authorized', $response->json()['token']);
                session()->put('user', $response->json()['user']);
                return Redirect::route('mainPage');
            } else if ($response->status() === 401) {
                return Redirect::route('auth.login')->withErrors($response->json())->withInput(
                    $request->except('password')
                );
            } else if ($response->status() === 422) {
                return Redirect::route('auth.login')->withErrors($response->json()['errors'])->withInput(
                    $request->except('password')
                );
            } else {
                return Redirect::route('auth.login')->withErrors(["message" => "server error code " . $response->status()])->withInput(
                    $request->except('password')
                );
            }
        }

    public function logout(Request $request){

        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH').'/api/auth/logout');
//        dd($response->json());
//        if ($response->ok()){
            session()->flush();
            return Redirect::route('auth.login.form');
//        }else{
//            return Redirect::back();
//        }

    }
}
