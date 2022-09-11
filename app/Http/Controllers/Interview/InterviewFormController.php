<?php

namespace App\Http\Controllers\Interview;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\Interview\InterviewFormRepository;
use App\Repositories\Interview\InterviewRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InterviewFormController extends Controller
{
    private $interviewRepository;
    private $companyRepository;
    private $interviewFormRepository;

    public function __construct(
        InterviewRepository $interviewRepository,
        CompanyRepository $companyRepository,
        InterviewFormRepository $interviewFormRepository
    )
    {
        $this->interviewRepository = $interviewRepository;
        $this->companyRepository = $companyRepository;
        $this->interviewFormRepository = $interviewFormRepository;
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

        return view('pages.interview.form.list')->with(
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


    public function viewOne($id)
    {
        return view('pages.interview.form.one')->with(
            [
                'user' => session()->get('user'),
                'id' => $id
            ]
        );
    }


    public function getByUserId($user_id)
    {
        return $this->interviewFormRepository->getByUserId($user_id);
    }

    public function getReportAll()
    {
        return $this->interviewFormRepository->getReportAll();
    }

    public function getById($id)
    {
        return $this->interviewFormRepository->getById($id);
    }



    public function index()
    {
        return view('pages.production.report_view')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function createViewForm()
    {
        return view('pages.interview.form.create')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function edit(Request $request)
    {
        return $this->interviewFormRepository->edit($request);
    }



    public function insert(Request $request)
    {
        return $this->interviewFormRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->interviewFormRepository->list($request);
    }

    public function delete($id)
    {
        return $this->interviewFormRepository->delete($id);
    }




    public function getByReportBetween($report_day_from,$report_day_to, $copmany_id)
    {
        return $this->interviewFormRepository->getByReportBetween($report_day_from,$report_day_to, $copmany_id);
    }


}


