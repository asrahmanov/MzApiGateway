<?php

namespace App\Http\Controllers\DataAggregation;

use App\Http\Controllers\Controller;
use App\Repositories\DataAggregation\BudgetRepository;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    private $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;
    }

    public function getById($id)
    {
        return $this->budgetRepository->getById($id);
    }

    public function edit(Request $request)
    {
        return $this->budgetRepository->edit($request);
    }

    public function insert(Request $request)
    {
        return $this->budgetRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->budgetRepository->list($request);
    }


}


