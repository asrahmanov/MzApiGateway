<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Repositories\Security\SecurityEventRepository;
use App\Repositories\Security\SecurityReportRepository;
use Illuminate\Http\Request;

class SecurityController extends Controller
{

    private $securityReportRepository;

    public function __construct(SecurityReportRepository $securityReportRepository)
    {
        $this->securityReportRepository = $securityReportRepository;
    }
// Отображени

//  создание отчета
    public function index(Request $request)
    {
        return view('pages.security.create')->with([
            'user' => session()->get('user')
        ]);
    }

    public function view(Request $request)
    {
        return view('pages.security.view')->with([
            'user' => session()->get('user')
        ]);
    }


// действиес данными
    public function insert(Request $request)
    {
        return $this->securityReportRepository->insert($request);
    }


    public function get_by_report_day($event_day, $copmany_id)
    {

        return $this->securityReportRepository->get_by_report_day($event_day, $copmany_id);
    }

}
