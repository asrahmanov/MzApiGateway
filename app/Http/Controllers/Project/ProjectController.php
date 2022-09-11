<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\ContractRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProjectController extends Controller
{

    private $projectRepository;
    private $companyRepository;
    private $contractRepository;

    public function __construct(ProjectRepository $projectRepository, CompanyRepository $companyRepository,ContractRepository $contractRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->companyRepository = $companyRepository;
        $this->contractRepository = $contractRepository;
    }

    // Создание проекта
    public function insert(Request $request)
    {
        return $this->projectRepository->insert($request);

    }

    // Создание отчета проекта
    public function reportCreate(Request $request)
    {
        return $this->projectRepository->reportCreate($request);
    }

    // Редактирование объекта
    public function update(Request $request)
    {
        $project = $this->projectRepository->update($request);

        $this->createContractsFromProject();

        return $project;
    }

    // Рендер страницы списка проектов
    public function index()
    {
        return view('pages.project.index')->with([
                'user' => session()->get('user')
            ]

        );
    }

    // Рендер страницы списка проектов по продуктам
    public function product()
    {
        return view('pages.project.product')->with([
                'user' => session()->get('user')
            ]

        );
    }

    // Рендер страницы списка проектов по продуктам
    public function productList()
    {
        return view('pages.project.productList')->with([
                'user' => session()->get('user')
            ]
        );
    }

    public function productReport(Request $request, $name)
    {
        return view('pages.project.productReport')->with([
                'user' => session()->get('user'),
                'name' => $name,
            ]
        );

    }

    // Рендер страницы одного проекта
    public function show(Request $request, $project_id)
    {
        return view('pages.project.show')->with([
                'user' => session()->get('user'),
                'project_id' => $project_id
            ]

        );

    }

    // Рендер страницы графика
    public function graph(Request $request)
    {
        $user_id = session()->get('user');
        return view('pages.project.graph')->with([
                'user' => $user_id,
            ]

        );

    }

    // Рендер страницы Отчета
    public function report(Request $request)
    {
        return view('pages.project.report')->with([
                'user' => session()->get('user'),
                'companies' => $this->companyRepository->getAll()
            ]
        );
    }
    // Рендер страницы дашборда
    public function dashboards(Request $request)
    {
        return view('pages.project.dashboards')->with([
                'user' => session()->get('user'),
                'companies' => $this->companyRepository->getAll()
            ]
        );
    }

    //  все типы проекта
    public function getType()
    {
        return Response::json($this->projectRepository->getType());
    }


    public function getAppointment()
    {
        return Response::json($this->projectRepository->getAppointment());
    }

    // Типы проекта
    public function getTypeView(Request $request)
    {
        $type = $this->projectRepository->getType();

        return view('pages.project.type')->with([
                'user' => session()->get('user'),
                'type' => $type
            ]
        );
    }


    // Проекты по user_id
    public function getProjectByUser(Request $request, $user_id)
    {
        return Response::json($this->projectRepository->getProjectByUser($user_id));
    }




    public function getProjectAll(Request $request)
    {
        return Response::json($this->projectRepository->getProjectAll());
    }


    // Один проект по id
    public function getProjectById(Request $request, $project_id)
    {
        return Response::json($this->projectRepository->getProjectById($project_id));
    }

    // Статусы проекта
    public function getProjectStatus(Request $request)
    {
        return Response::json($this->projectRepository->getProjectStatus());
    }

    // Статусы проекта
    public function getStatusView(Request $request)
    {
        $status = $this->projectRepository->getProjectStatus();

        return view('pages.project.status')->with([
                'user' => session()->get('user'),
                'status' => $status
            ]
        );
    }


    // Этапы проекта
    public function getStage(Request $request)
    {
        return Response::json($this->projectRepository->getStage());
    }

    // Этапы проекта
    public function getStageView(Request $request)
    {
         $stage = $this->projectRepository->getStage();

        return view('pages.project.stage')->with([
                'user' => session()->get('user'),
                'stage' => $stage
            ]
        );
    }

    // Отчеты проекта
    public function getReport(Request $request, $project_id)
    {
        return Response::json($this->projectRepository->getReport($project_id));
    }

    // все отчеты юзера
    public function getReportByUser(Request $request, $user_id)
    {
        return Response::json($this->projectRepository->getReportByUser($user_id));
    }

    // Все Отчкеты
    public function getReportAll(Request $request)
    {
        return Response::json($this->projectRepository->getReportAll());
    }

    // все отчеты юзера
    public function getLastReport(Request $request, $project_id)
    {
        return Response::json($this->projectRepository->getLastReport($project_id));
    }

    //импорт проектов со stage_id = 5 в контракты
    protected function createContractsFromProject (){
        $projects = $this->projectRepository->getProjectByStageId(5);

        $contracts = $this->contractRepository->getContractAll();

        foreach ($projects as $project_item) {
                //есть ли проект в контрактах
                $status = false;
                foreach ($contracts as $contract_item) {
                    if ($project_item['id'] === $contract_item['project_id']) {
                        $status = true;
                    }
                }

                if (!$status) {
                    $this->contractRepository->insert(new Request(array_merge(['project_id' => $project_item['id']], $project_item)));
                }
        }
    }
    // Страница удаления всего
    public function redButton()
    {
        return view('pages.redButton')->with([
                'user' => session()->get('user'),
            ]
        );
    }
    //Удаление всего
    public function deleteAll(Request $request)
    {
        return Response::json($this->projectRepository->deleteAll());
    }
}
