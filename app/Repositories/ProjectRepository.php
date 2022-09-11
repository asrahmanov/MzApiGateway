<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class ProjectRepository
{

    // Получение всех типо проекта
    public function getType()
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getType");
        return $response->json();
    }


    /*
     * 'user_id' => $request->user_id,
     * 'counterparty_id' => $request->counterparty_id,
     * 'company_id' => $request->company_id,
     * 'name' => $request->name,
     * 'price_probable' => $request->price_probable,
     * 'price' => $request->price,
     * 'price_nds' => $request->price_nds,
     * 'probability' => $request->probability,
     * 'type_id' => $request->type_id,
     * 'state_comment' => $request->state_comment,
     * 'description' => $request->description,
     * 'status_id' => $request->status_id,
     * 'stage_id' => $request->stage_id,
     * 'product_name' => $request->product_name,
     * 'appointment_id' => $request->appointment_id,
     * 'code' => $request->code
     *
     * */
    // Создание проекта
    public function insert(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_PROJECTS') . "/api/project/insert", $request->all());

        if ($response->ok() || $response->status() === 201){

            return $response->json() ;
        }else{
            return['errors'=>$response->status()];
        }

    }

    // Редактирование проекта
    public function update(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_PROJECTS') . "/api/project/update", $request->all());

        return $response->json();
    }


    // Создаение отчета  проекта
    public function reportCreate(Request $request)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized'),
        ])->post(config('api.API_PROJECTS') . "/api/project/reportCreate", $request->all());
        return $response->json();
    }

    public function getProjectByUser($user_id)
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getProjectByUser", ['user_id' => $user_id]);
        return $response->json();
    }

    public function getProjectAll()
    {
        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getProjectAll");

        return $response->json();

    }


    public function getProjectById($project_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getProjectById", ['project_id' => $project_id]);
        return $response->json();
    }

    public function getProjectByStageId($stage_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getProjectByStageId", ['stage_id' => $stage_id]);

        return $response->json();
    }

    public function getProjectStatus()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getProjectStatus");
        return $response->json();
    }


    public function getAppointment()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getAppointment");
        return $response->json();
    }


    public function getStage()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getStage");
        return $response->json();
    }


    public function getReport($project_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getReport", ['project_id' => $project_id]);
        return $response->json();
    }


    public function getReportByUser($user_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getReportByUser", ['user_id' => $user_id]);
        return $response->json();
    }


    public function getReportAll()
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getReportAll");
        return $response->json();

    }

    public function getLastReport($project_id)
    {

        $response = Http::withHeaders([
            'Authorization' => session('authorized')
        ])->post(config('api.API_PROJECTS') . "/api/project/getLastReport", ['project_id' => $project_id]);
        return $response->json();
    }

    public function deleteAll()
    {
        return 2;
    }



}
