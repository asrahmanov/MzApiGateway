<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\Production\ProductionReportRepository;
use Illuminate\Http\Request;

class ProductionReportController extends Controller
{
    private $productionReportRepository;
    private $companyRepository;

    public function __construct(ProductionReportRepository $productionReportRepository, CompanyRepository $companyRepository)
    {
        $this->productionReportRepository = $productionReportRepository;
        $this->companyRepository = $companyRepository;
    }


    public function addReport()
    {
        return view('pages.production.add_report')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }



    public function index()
    {
        return view('pages.production.report_view')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function dashBoard()
    {
        return view('pages.production.dashBoard')->with(
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
        return view('pages.production.export')->with(
            [
                'report' => $this->productionReportRepository->getReportByReportDayandCopmanyID($report_day, $copmany_id),
                'companies' => $companies
            ]
        );
    }


    public function insert(Request $request)
    {
        return $this->productionReportRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->productionReportRepository->list($request);
    }


    public function getReportByReportDayandCopmanyID($report_day, $copmany_id)
    {
        return $this->productionReportRepository->getReportByReportDayandCopmanyID($report_day, $copmany_id);
    }

    public function getByReportBetween($report_day_from,$report_day_to, $copmany_id)
    {
        return $this->productionReportRepository->getByReportBetween($report_day_from,$report_day_to, $copmany_id);
    }


}


