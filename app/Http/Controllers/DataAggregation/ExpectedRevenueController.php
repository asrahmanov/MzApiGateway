<?php

namespace App\Http\Controllers\DataAggregation;

use App\Http\Controllers\Controller;
use App\Repositories\DataAggregation\ExpectedRevenueRepository;
use Illuminate\Http\Request;

class ExpectedRevenueController extends Controller
{
    private $expectedRevenueRepository;

    public function __construct(ExpectedRevenueRepository $expectedRevenueRepository)
    {
        $this->expectedRevenueRepository = $expectedRevenueRepository;
    }



    public function getById($id)
    {
        return $this->expectedRevenueRepository->getById($id);
    }


    public function getByCompanyName($id)
    {
        return $this->expectedRevenueRepository->getByCompanyName($id);
    }

    public function edit(Request $request)
    {
        return $this->expectedRevenueRepository->edit($request);
    }

    public function insert(Request $request)
    {
        return $this->expectedRevenueRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->expectedRevenueRepository->list($request);
    }

    public function getGroup(Request $request)
    {
        return $this->expectedRevenueRepository->getGroup($request);
    }



}


