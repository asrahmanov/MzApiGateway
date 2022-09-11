<?php

namespace App\Http\Controllers\Interview;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\Interview\InterviewRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InterviewController extends Controller
{
    private $interviewRepository;
    private $companyRepository;

    public function __construct(InterviewRepository $interviewRepository, CompanyRepository $companyRepository)
    {
        $this->interviewRepository = $interviewRepository;
        $this->companyRepository = $companyRepository;
    }


    public function viewReport()
    {
        return view('pages.interview.view')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

    public function viewReportAll()
    {


        return view('pages.interview.view_all')->with(
            [
                'user' => session()->get('user'),
            ]
        );
    }

    public function viewClose()
    {
        return view('pages.interview.view_close')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function createWorkSheets()
    {
        return view('pages.interview.Worksheets.worksheet_create')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function viewOne($id)
    {
        return view('pages.interview.one')->with(
            [
                'user' => session()->get('user'),
                'id' => $id
            ]
        );
    }


    public function getByUserId($user_id)
    {
        return $this->interviewRepository->getByUserId($user_id);
    }

    public function getReportAll()
    {
        return $this->interviewRepository->getReportAll();
    }

    public function getById($id)
    {
        return $this->interviewRepository->getById($id);
    }


    public function createWorkSheetsforUser(Request $request)
    {
        return $this->interviewRepository->insert($request);
    }



    public function index()
    {
        return view('pages.production.report_view')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function edit(Request $request)
    {
        return $this->interviewRepository->edit($request);
    }



    public function insert(Request $request)
    {
        return $this->interviewRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->interviewRepository->list($request);
    }




    public function getByReportBetween($report_day_from,$report_day_to, $copmany_id)
    {
        return $this->interviewRepository->getByReportBetween($report_day_from,$report_day_to, $copmany_id);
    }


}


