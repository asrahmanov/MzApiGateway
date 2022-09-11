<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyRepository
{

    public function all(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH').'/api/companies/getCompany');
            return $response->json();
    }

    public function getAll(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH').'/api/companies/all');
        return $response->json();
    }

    public function getAllDzo(){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH').'/api/companies/all');

        $result = [];
        foreach ($response->json() as $item){
            if ($item['parent_id'] !== 0){
                $result[] =$item;
            }
        }

        return $result;
    }

    public function one($id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized'),
            'company_id'=>$id,
        ])->post(config('api.API_AUTH')."/api/companies/companiesOne/{$id}");
        return $response->json();
    }

    public function parentList(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH')."/api/companies/parentList/{$request->company_id}");
        return $response->json();
    }


    public function getByUser($user_id){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized')
        ])->post(config('api.API_AUTH')."/api/companies/getByUser/{$user_id}");
        return $response->json();
    }



    /*
     * 'id' => $request->id,
     * 'name' => $request->name,
     * 'inn' => $request->inn,
     * 'phone' => $request->phone,
     * 'description' => $request->description,
     * */
    public function update(Request $request){

        $response = Http::withHeaders([
            'Authorization'=>session('authorized'),
        ])->put(config('api.API_AUTH')."/api/companies/save", $request->all());
        return $response->json();

    }

    /*
     * 'parent_id' =>$request->parent_id,
     *  'name' => $request->name,
     *  'inn' => $request->inn,
     *  'phone' => $request->phone,
     *  'description' => $request->description,
     *
     * */
    public function insert(Request $request){
        $response = Http::withHeaders([
            'Authorization'=>session('authorized'),
        ])->post(config('api.API_AUTH')."/api/companies/add", $request->all());
        if ($response->ok() || $response->status() === 201){

            return $response->json();
        }else{
            return['errors'=>$response->status()];
        }
    }




}
