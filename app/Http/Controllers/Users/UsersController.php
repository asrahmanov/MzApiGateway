<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    private $usersRepository;

    public function __construct(UserRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function login($login, $password)
    {
        $res = $this->usersRepository->login($login, $password);
        if (isset($res['message'])) {
            return $res['message'];
        }

        if(isset($res['token'])) {
            session()->put('authorized', $res['token']);
            session()->put('user',$res['user']);
            return true;
        }

        return 'Ошибка авторизации';

    }

    public function logout(Request $request){

        session()->flush();
        return Redirect::route('users.auth');

    }
}


