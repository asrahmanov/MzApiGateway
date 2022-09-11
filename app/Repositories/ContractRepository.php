<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class ContractRepository
{

    // Получение всех типо контрактов
    public function getType()
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getType");
        return $response->json();
    }


    /*
     *  'project_id'=>$request->project_id??null,
     *  'company_id' => $request->company_id,
     *  'counterparty_id' => $request->counterparty_id,
     *  'user_id' => $request->user_id,
     *  'stage_id' => $request->stage_id,
     *  'status_id' => $request->status_id,
     *  'type_id' => $request->type_id,
     *  'code' => $request->code,
     *  'name' => $request->name,
     *  'price' => $request->price,
     *  'price_probable' => $request->price_probable,
     *  'price_nds' => $request->price_nds,
     *  'description' => $request->description,
     *  'state_comment' => $request->state_comment,
     *  'probability' => $request->probability,
     *
     * */
    // Создание контракта
    public function insert(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/insert", $request->all());

        if ($response->ok() || $response->status() === 201){

            return $response->json() ;
        }else{
            return['errors'=>$response->status()];
        }
    }

    // Редактирование контракта
    public function update(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/update", $request->all());

        return $response->json();
    }

    // Создаение отчета  контракта
    public function reportCreate(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/reportCreate", $request->all());

        return $response->json();
    }

    public function getContractByUser($user_id)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getContractByUser", ['user_id' => $user_id]);
        return $response->json();
    }

    public function getContractAll()
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getContractAll");

        return $response->json();
    }

    public function getContractById($contract_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getContractById", ['contract_id' => $contract_id]);
        return $response->json();
    }

    public function getContractStatus()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getContractStatus");
        return $response->json();
    }

    public function getStage()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getStage");
        return $response->json();
    }

    public function getReport($contract_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getReport", ['contract_id' => $contract_id]);
        return $response->json();
    }

    public function getReportByUser($user_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getReportByUser", ['user_id' => $user_id]);
        return $response->json();
    }

    public function getReportAll()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getReportAll");
        return $response->json();
    }

    public function getLastReport($contract_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_CONTRACTS') . "/api/Contract/getLastReport", ['contract_id' => $contract_id]);
        return $response->json();
    }


}
