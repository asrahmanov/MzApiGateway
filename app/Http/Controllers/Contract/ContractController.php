<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\ContractRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ContractController extends Controller
{

    private $contractRepository;
    private $companyRepository;

    public function __construct(ContractRepository $contractRepository, CompanyRepository $companyRepository)
    {
        $this->contractRepository = $contractRepository;
        $this->companyRepository = $companyRepository;
    }

    // Создание контракта
    public function insert(Request $request)
    {
        return $this->contractRepository->insert($request);
    }

    // Создание отчета контракта
    public function reportCreate(Request $request)
    {
        return $this->contractRepository->reportCreate($request);
    }

    // Редактирование объекта
    public function update(Request $request)
    {
        return $this->contractRepository->update($request);
    }

    // Рендер страницы списка контрактов
    public function index()
    {
        return view('pages.contract.index')->with([
                'user' => session()->get('user')
            ]

        );
    }

    // Рендер страницы списка контрактов по продуктам
    public function product()
    {
        return view('pages.contract.product')->with([
                'user' => session()->get('user')
            ]

        );
    }

    // Рендер страницы одного контракта
    public function show(Request $request, $contract_id)
    {
        return view('pages.contract.show')->with([
                'user' => session()->get('user'),
                'contract_id' => $contract_id
            ]

        );

    }

    // Рендер страницы графика
    public function graph(Request $request)
    {
        $user_id = session()->get('user');
        return view('pages.contract.graph')->with([
                'user' => $user_id,
            ]

        );

    }

    // Рендер страницы Отчета
    public function report(Request $request)
    {
        return view('pages.contract.report')->with([
                'user' => session()->get('user'),
                'companies' => $this->companyRepository->getAll()
            ]
        );
    }
    // Рендер страницы дашборда
    public function dashboards(Request $request)
    {
        return view('pages.contract.dashboards')->with([
                'user' => session()->get('user'),
                'companies' => $this->companyRepository->getAll()
            ]
        );
    }

    //  все типы контракта
    public function getType()
    {
        return Response::json($this->contractRepository->getType());
    }


    public function getAppointment()
    {
        dd('dddddddd');
        return Response::json($this->contractRepository->getAppointment());
    }

    // Типы контракта
    public function getTypeView(Request $request)
    {
        $type = $this->contractRepository->getType();

        return view('pages.contract.type')->with([
                'user' => session()->get('user'),
                'type' => $type
            ]
        );
    }


    // контракты по user_id
    public function getContractByUser(Request $request, $user_id)
    {
        return Response::json($this->contractRepository->getContractByUser($user_id));
    }




    public function getContractAll(Request $request)
    {
        return Response::json($this->contractRepository->getContractAll());
    }


    // Один контракт по id
    public function getContractById(Request $request, $contract_id)
    {
        return Response::json($this->contractRepository->getContractById($contract_id));
    }

    // Статусы контракта
    public function getContractStatus(Request $request)
    {
        return Response::json($this->contractRepository->getContractStatus());
    }

    // Статусы контракта
    public function getStatusView(Request $request)
    {
        $status = $this->contractRepository->getContractStatus();

        return view('pages.contract.status')->with([
                'user' => session()->get('user'),
                'status' => $status
            ]
        );
    }


    // Этапы контракта
    public function getStage(Request $request)
    {
        return Response::json($this->contractRepository->getStage());
    }

    // Этапы контракта
    public function getStageView(Request $request)
    {
         $stage = $this->contractRepository->getStage();

        return view('pages.contract.stage')->with([
                'user' => session()->get('user'),
                'stage' => $stage
            ]
        );
    }

    // Отчеты контракта
    public function getReport(Request $request, $contract_id)
    {
        return Response::json($this->contractRepository->getReport($contract_id));
    }
    // Все Отекеты
    public function getReportAll(Request $request)
    {
        return Response::json($this->contractRepository->getReportAll());
    }

    // все отчеты юзера
    public function getReportByUser(Request $request, $user_id)
    {
        return Response::json($this->contractRepository->getReportByUser($user_id));
    }

    // все отчеты юзера
    public function getLastReport(Request $request, $contract_id)
    {
        return Response::json($this->contractRepository->getLastReport($contract_id));
    }
}
