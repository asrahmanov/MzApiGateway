<?php

namespace App\Http\Controllers\DataAggregation;

use App\Http\Controllers\Controller;
use App\Repositories\DataAggregation\ContractAndFactRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContractAndFactController extends Controller
{
    private $contractAndFactRepository;

    public function __construct(ContractAndFactRepository $contractAndFactRepository)
    {
        $this->contractAndFactRepository = $contractAndFactRepository;
    }


    public function getById($id)
    {
        return $this->contractAndFactRepository->getById($id);
    }


    public function getByCompanyName($id)
    {
        return $this->contractAndFactRepository->getByCompanyName($id);
    }

    public function edit(Request $request)
    {
        return $this->contractAndFactRepository->edit($request);
    }

    public function insert(Request $request)
    {
        return $this->contractAndFactRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->contractAndFactRepository->list($request);
    }



}


