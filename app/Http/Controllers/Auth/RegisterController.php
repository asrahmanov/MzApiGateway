<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    public function form(){

    }
    public function registration(Request $request){

        if ($request->password !== $request->confirm_password){
            return Redirect::route('auth.registration.form')->withErrors(['confirm_password'=>"passwords don't match"])->withInput(
                $request->except('password')
            );
        }

        $response = Http::post(config('api.API_AUTH').'/api/auth/registration',$request->all());
        if ($response->ok()){
            session()->put('authorized',$response->json()['token']);
            session()->put('user',$response->json()['user']);
            return Redirect::route('home');
        }else if ($response->status() === 401){
            return Redirect::route('auth.registration.form')->withErrors($response->json())->withInput(
                $request->except('password')
            );
        }else if ($response->status() === 422){
            return Redirect::route('auth.registration.form')->withErrors($response->json()['errors'])->withInput(
                $request->except('password')
            );
        }else{
            return Redirect::route('auth.registration.form')->withErrors(["message"=> "server error code ".$response->status()])->withInput(
                $request->except('password')
            );
        }
    }
    public function registrationValidator(Request $request){
        $response = Http::post(config('api.API_AUTH').'/api/auth/registration-validator',$request->all());
        return Response::json($response->body(),$response->status());
    }


//    use RegistersUsers;
//
//    /**
//     * Where to redirect users after registration.
//     *
//     * @var string
//     */
//    protected $redirectTo = '/home';
//
//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('guest');
//    }
//
//    /**
//     * Get a validator for an incoming registration request.
//     *
//     * @param  array  $data
//     * @return \Illuminate\Contracts\Validation\Validator
//     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
//        ]);
//    }
//
//    /**
//     * Create a new user instance after a valid registration.
//     *
//     * @param  array  $data
//     * @return \App\User
//     */
//    protected function create(array $data)
//    {
//        return User::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'password' => Hash::make($data['password']),
//        ]);
//    }



}
