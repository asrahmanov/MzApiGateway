<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\HR\HrReportCovidRepository;
use Illuminate\Http\Request;

class HrReportCovidController extends Controller
{
    private $hrReportCovidRepository;
    private $companyRepository;

    public function __construct(HrReportCovidRepository $hrReportCovidRepository, CompanyRepository $companyRepository)
    {
        $this->hrReportCovidRepository = $hrReportCovidRepository;
        $this->companyRepository = $companyRepository;
    }


    public function addReport()
    {
        return view('pages.hr.covid')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function report_covid()
    {
        return view('pages.hr.report_covid')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function index()
    {
        return view('pages.hr.index')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function ImportExel($report_day, $copmany_id)
    {
        $copmany = $this->companyRepository->getAll();
        $companies = [];
        foreach ($copmany as $key => $value) {
            $companies[$copmany[$key]['id']] =  $copmany[$key]['name'];
        }
        return view('pages.hr.export_covid')->with(
            [
                'report' => $this->hrReportCovidRepository->getReportByReportDayandCopmanyID($report_day, $copmany_id),
                'companies' => $companies
            ]
        );
    }


    public function insert(Request $request)
    {
        return $this->hrReportCovidRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->hrReportCovidRepository->list($request);
    }


    public function getReportByReportDayandCopmanyID($report_day, $copmany_id)
    {
        return $this->hrReportCovidRepository->getReportByReportDayandCopmanyID($report_day, $copmany_id);
    }


}


