<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use App\Repositories\ContractRepository;
use App\Repositories\CounterpartiesRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportFromExcelController extends Controller
{

    private $projectRepository;
    private $companyRepository;
    private $contractRepository;
    private $counterpartiesRepository;

    public function __construct(
        ProjectRepository        $projectRepository,
        CompanyRepository        $companyRepository,
        ContractRepository       $contractRepository,
        CounterpartiesRepository $counterpartiesRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->companyRepository = $companyRepository;
        $this->contractRepository = $contractRepository;
        $this->counterpartiesRepository = $counterpartiesRepository;
    }

    public function importProjectsAndContracts(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('pages.import')->with([
                    'user' => session()->get('user'),
                ]
            );
        }
        $errors = [];

        //проверяем обязательные поля
        if (
            !$request->exists('user_id')
            || !$request->exists('file')
        ) {
            $error = 'fields: ';
            $error .= !$request->exists('user_id') ? ' user_id, ' : '';
            $error .= !$request->exists('file') ? ' file, ' : '';

            $error .= ' required!';

            $errors[] = [
                "row" => 0,
                "text" => $error,
            ];

            return \Response::json($errors, 400);
        }


        $dzoCompanies = $this->companyRepository->getAllDzo();
        $counterparties = $this->counterpartiesRepository->list();
        $allProjects = $this->projectRepository->getProjectAll();
        $allProjects2 = $this->projectRepository->getProjectByStageId(['stage_id' => 5]);
        $allProjects = array_merge($allProjects, $allProjects2);

        $projectStages = $this->projectRepository->getStage();
        $projectAppointment = $this->projectRepository->getAppointment();
        $allContracts = $this->contractRepository->getContractAll();
        $allReportsProject = $this->projectRepository->getReportAll();
        $allReportsContract = $this->contractRepository->getReportAll();

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 0);
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);

        $spreadsheet = $reader->load($request->file('file')->path());

        //-1 что бы обрезать итого
        $num_rows = $spreadsheet->getActiveSheet()->getHighestRow() - 1;

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                "C4:W$num_rows",     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                true,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );

        $upload_date = $spreadsheet->getActiveSheet()->getCell('D1')->getValue();

        if (is_numeric($upload_date)){
            $request->merge([
                'upload_date' => Date::excelToDateTimeObject($upload_date)->format('Y-m-d')
            ]);
        }else{
            $request->merge([
                'upload_date' => date('Y-m-d',strtotime($upload_date))
            ]);
        }



        $result = [];

        $result2 = [];


        foreach ($dataArray as $key => $item) {
            $currentProject = null;
            if (mb_strpos(mb_strtolower($item['C']), 'итого') !== false) {
                break;
            }

            $currentContract = null;
            $currentReport = null;
            $newProject = null;
            $newContract = null;
            $newReport = null;

            //собираем Проект

            $dzo_name = $item['C'];//ДЗО

            $counterparty_name = $item['D'];//Заказчик
            $counterparty_inn = $item['E'];//инн
            $counterparty_kpp = $item['F'];//кпп

            $contract_name = $item['G'];//Наименование / шифр контракта
            $contract_number = $item['H'];//Договор №_ от_
            $contract_end_date = $item['I'];//Дата завершения работ по договору

            $project_name = $item['J'];//Наименование проекта
            $project_stage = $item['K'];//Стадия проекта
            $probability = $item['L'];//Вероятность
            $appointment = $item['M'];//Поставка / услуга / аренда / услуги по аренде
            $products = $item['N'];//Продукция / перечень продукции

            $actual_revenue = $item['O']; //фактическая выручка
            $current_contracting = $item['P'];  //текущая контрактация
            $project_price = $item['Q']; //Перспективные проекты 2022 млн. руб.

            $prognosis_1q = $item['R'];
            $prognosis_2q = $item['S'];
            $prognosis_3q = $item['T'];
            $prognosis_4q = $item['U'];
            $comment = $item['W'];

            if (is_numeric($contract_end_date)){
                $contract_end_date = Date::excelToDateTimeObject($contract_end_date)->format('Y-m-d');
            }else{
                    $contract_end_date = date('Y-m-d',strtotime($contract_end_date));
            }



            $currentProject = [
                'company_id' => search_in_array_objects($dzo_name, $dzoCompanies),//ДЗО
                'counterparty_id' => search_in_array_objects($counterparty_name, $counterparties),//Заказчик
                'appointment_id' => search_in_array_objects($appointment, $projectAppointment),//Продукция // Поставка/услуга
                'product_name' => $products,//Продукты // Продукция/перечень продукции

                'name' => $project_name, //Перспективный проект // наименование
                'stage_id' => strlen($contract_name) ? 5 : search_in_array_objects($project_stage, $projectStages), //Стадия проекта

                'status_id' => 1,//статус

                'probability' => (bool)$probability ? (is_numeric($probability)?$probability:50) : 0,

                'price' => !empty($project_price) ? $project_price : 0,//Перспективные проекты 2022 млн. руб.

                'state_comment' => $comment,

                'user_id' => $request->user_id,

                'description' => 'Нет описания',

            ];

            if ($dzo_name === '') {
                break;
                $errors[] = [
                    "row" => $key,
                    "text" => 'пустое имя ДЗО ' . $item['C'],
                ];

            }


            //валидация проекта  и создание
            if (!$currentProject['company_id']) {

                $newCompanyDzo = $this->companyRepository->insert(new Request(
                    [
                        'parent_id' => 1,
                        'name' => $dzo_name,
                        'inn' => 0
                    ]
                ));
                $dzoCompanies[] = $newCompanyDzo['company'];

                $currentProject['company_id'] = $newCompanyDzo['company']['id'];

            }

            //валидация заказчика
            //если нет, то создаем
            if (!$currentProject['counterparty_id']) {
                if ($counterparty_name === '') {
                    $errors[] = [
                        "row" => $key,
                        "text" => 'не заполнен заказчик ',
                    ];
                    continue;
                }

                $newCounterparty = $this->counterpartiesRepository->insert(new Request(
                    [
                        'inn' => strlen($counterparty_inn)?$counterparty_inn:'0',
                        'name' => $counterparty_name,
                    ]
                ));

                $counterparties[] = $newCounterparty;

                $currentProject['counterparty_id'] = $newCounterparty['id'];
            }

            if (!$currentProject['appointment_id']) {
                $errors[] = [
                    "row" => $key,
                    "text" => 'Типа продукции нет в справочнике: ' . $item['M']
                ];
                continue;
            }


            if (!(bool)$currentProject['name']) {
                //если имя проекта не заполнено то пишем в него название договора
                $currentProject['name'] = $contract_number;
//                $errors[]=[
//                    "row"=>$key,
//                    "text"=>'Название проекта не может быть пустым'
//                ];
//                continue;
            }

            if (!$currentProject['stage_id']) {
                $currentProject['stage_id'] = 1;
//                $errors[]=[
//                    "row"=>$key,
//                    "text"=>'Нет такой стадии'.$item['K']
//                ];
//                continue;
            }


            //проверяем есть ли такой проект
            foreach ($allProjects as $project) {
                if (
                    $currentProject['company_id'] === $project['company_id']
                    && $currentProject['counterparty_id'] === $project['counterparty_id']
                    && $currentProject['name'] === $project['name']
//                    && $currentProject['price'] == $project['price']
                ) {
                    //если есть записываем в переменную
                    $newProject = $project;
                    break;
                }
            }

            //если нету проекта то создаем его
            if (!$newProject) {

                $newProject = $this->projectRepository->insert(new Request($currentProject));
            }

            if (array_key_exists('errors', $newProject)) {
                $errors[] = [
                    "row" => $key,
                    "text" => 'Ошибка при загрузке проекта: ' . $newProject['errors']
                ];
                continue;
            }

            //собираем Контракт если он есть
            if (!empty($current_contracting)) {
                try {
                    $currentContract = array_merge($currentProject, [
                        'contract_number' => $contract_number,
                        'start_date' => null,
                        'end_date' => strlen($contract_end_date) ? $contract_end_date : null,
                        'price' => $current_contracting,//Текущая контрактация на 2022г. (на дату)
                        'project_id' => $newProject['id']
                    ]);
                }catch (\ErrorException $e){
                    dd($contract_end_date);
                }


                //проверяем есть ли такой контракт
                foreach ($allContracts as $contract) {

                    if (
                        $currentContract['company_id'] === $contract['company_id']
                        && $currentContract['counterparty_id'] === $contract['counterparty_id']
                        && $currentContract['contract_number'] === $contract['contract_number']
//                        && $currentContract['start_date'] === $contract['start_date']
//                        && $currentContract['end_date'] === $contract['end_date']
                    ) {
                        //если есть записываем в переменную
                        $newContract = $contract;
                        break;
                    }
                }

                //если нету контракта то создаем его
                if (!$newContract) {
                    $newContract = $this->contractRepository->insert(new Request($currentContract));

                }

                if (array_key_exists('errors', $newContract)) {
                    $errors[] = [
                        "row" => $key,
                        "text" => 'Ошибка при загрузке контракта: ' . $newContract['errors']
                    ];
                    continue;
                }
            }


            $currentReport = [
                'user_id' => $request->user_id,
                'quarter_1' => empty($prognosis_1q) ? 0 : $prognosis_1q,
                'quarter_2' => empty($prognosis_2q) ? 0 : $prognosis_2q,
                'quarter_3' => empty($prognosis_3q) ? 0 : $prognosis_3q,
                'quarter_4' => empty($prognosis_4q) ? 0 : $prognosis_4q,
                'report_date' => $request->upload_date,
                'current_contracting'=>!empty($current_contracting) ? $current_contracting : 0,
                'actual_revenue'=>!empty($actual_revenue) ? $actual_revenue : 0,
            ];



//            if (!$currentReport['quarter_1'] && !$currentReport['quarter_1']) {
//                continue;
//            }
            //собираем отчет
            //если нет контракта то пишем отчет в проект
            if (is_null($currentContract)) {

                $currentReport['project_id'] = $newProject['id'];

                //проверяем есть ли такой отчет
                foreach ($allReportsProject as $projectReport) {

                    if (
                        $currentReport['project_id'] === $projectReport['project_id']
                        && $currentReport['report_date'] === $projectReport['report_date']
                    ) {
                        //если есть записываем в переменную
                        $newReport = $projectReport;
                        break;
                    }
                }


                //если нету контракта то создаем его
                if (!$newReport) {
                    $newReport = $this->projectRepository->reportCreate(new Request($currentReport));
                }

            } else {

                $currentReport['contract_id'] = $newContract['id'];
                //проверяем есть ли такой отчет
                foreach ($allReportsContract as $contractReport) {
                    if (
                        $currentReport['contract_id'] === $contractReport['contract_id']
                        && $currentReport['report_date'] === $contractReport['report_date']
                    ) {
                        //если есть записываем в переменную
                        $newReport = $contractReport;
                        break;
                    }
                }

                //если нету контракта то создаем его
                if (!$newReport) {
                    $newReport = $this->contractRepository->reportCreate(new Request($currentReport));
                }

            }
            if (is_array($newReport) && array_key_exists('errors', $newReport)) {
                $errors[] = [
                    "row" => $key,
                    "text" => 'Ошибка при загрузке отчета: ' . $newReport['errors']
                ];
                continue;
            }



        }
//        dd(['errors'=>$errors]);
        return \Response::json(['errors' => $errors], 200);


    }

}
