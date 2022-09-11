<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\HR\HrReportTypeRepository;

class HrReportTypeController extends Controller
{
    private $hrReportTypeRepository;

    public function __construct(HrReportTypeRepository $hrReportTypeRepository)
    {
        $this->hrReportTypeRepository = $hrReportTypeRepository;
    }

    public function index()
    {
        return view('pages.hr.index')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }



    public function insert(Request $request)
    {
        return $this->hrReportTypeRepository->insert($request);
    }

    public function list()
    {
        return $this->hrReportTypeRepository->list();
    }





}


