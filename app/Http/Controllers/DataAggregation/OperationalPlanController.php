<?php

namespace App\Http\Controllers\DataAggregation;

use App\Http\Controllers\Controller;
use App\Repositories\DataAggregation\OperationalPlanRepository;
use Illuminate\Http\Request;

class OperationalPlanController extends Controller
{
    private $operationalPlanRepository;

    public function __construct(OperationalPlanRepository $operationalPlanRepository)
    {
        $this->operationalPlanRepository = $operationalPlanRepository;
    }


    public function getById($id)
    {
        return $this->operationalPlanRepository->getById($id);
    }


    public function getByCompanyName($id)
    {
        return $this->operationalPlanRepository->getByCompanyName($id);
    }

    public function edit(Request $request)
    {
        return $this->operationalPlanRepository->edit($request);
    }

    public function insert(Request $request)
    {
        return $this->operationalPlanRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->operationalPlanRepository->list($request);
    }





}


