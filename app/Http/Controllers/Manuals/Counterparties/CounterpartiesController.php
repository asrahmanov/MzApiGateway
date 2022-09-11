<?php

namespace App\Http\Controllers\Manuals\Counterparties;

use App\Http\Controllers\Controller;
use App\Repositories\CounterpartiesRepository;
use Illuminate\Http\Request;

class CounterpartiesController extends Controller
{


    private $counterpartiesRepository;

    public function __construct(CounterpartiesRepository $counterpartiesRepository)
    {
        $this->counterpartiesRepository = $counterpartiesRepository;
    }

    public function list(Request $request)
    {
        return $this->counterpartiesRepository->list($request);
    }

    public function checkinn(Request $request)
    {
        return $this->counterpartiesRepository->checkinn($request);
    }

    public function insert(Request $request)
    {
        return $this->counterpartiesRepository->insert($request);
    }


    public function create(Request $request)
    {
        return view('pages.manuals.counterparties.create')->with([
            'user' => session()->get('user')
        ]);
    }


    public function index()
    {

        return view('pages.manuals.counterparties.all')->with([
                'user' => session()->get('user')
            ]

        );

    }

}
