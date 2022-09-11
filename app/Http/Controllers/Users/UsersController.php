<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UsersController extends Controller
{
    private $usersRepository;

    public function __construct(UserRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function insert(Request $request)
    {
        return $this->usersRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->usersRepository->list($request);
    }

    public function listDzo(Request $request)
    {
        return $this->usersRepository->listDzo($request);
    }

    public function edit(Request $request)
    {
        return $this->usersRepository->edit($request);
    }


    public function getOne($user_id)
    {
        return $this->usersRepository->getOne($user_id);
    }


    public function getRole()
    {

        return $this->usersRepository->getRole();
    }



    public function userView()
    {
        return view('pages.users.index')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function userViewDzo()
    {
        return view('pages.users.dzo')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function createViewDzo()
    {
        return view('pages.users.createdzo')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function userInfo($user_id)
    {
        return view('pages.users.info')->with(
            [
                'user' => session()->get('user'),
                'user_id' => $user_id
            ]
        );
    }

    public function userInfoOne($user_id)
    {
        $user = session()->get('user');
        if($user_id == $user['id']) {
        return view('pages.users.one')->with(
            [
                'user' => session()->get('user'),
                'user_id' => $user_id
            ]
        );
        }

        return view('pages.error.403')->with(
            [
                'user' => session()->get('user'),
                'user_id' => $user_id
            ]
        );
    }


    public function createView()
    {
        return view('pages.users.create')->with(
            [
                'user' => session()->get('user'),
            ]
        );
    }

}


