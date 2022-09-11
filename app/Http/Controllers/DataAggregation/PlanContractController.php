<?php

namespace App\Http\Controllers\DataAggregation;

use App\Http\Controllers\Controller;
use App\Repositories\DataAggregation\PlanContractRepository;
use Illuminate\Http\Request;

class PlanContractController extends Controller
{
    private $planContractRepository;

    public function __construct(PlanContractRepository $planContractRepository)
    {
        $this->planContractRepository = $planContractRepository;
    }



    public function getById($id)
    {
        return $this->planContractRepository->getById($id);
    }


    public function getByCompanyName($id)
    {
        return $this->planContractRepository->getByCompanyName($id);
    }

    public function edit(Request $request)
    {
        return $this->planContractRepository->edit($request);
    }

    public function insert(Request $request)
    {
        return $this->planContractRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->planContractRepository->list($request);
    }

    public function getGroup(Request $request)
    {
        return $this->planContractRepository->getGroup($request);
    }



}


