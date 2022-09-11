<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\HR\HrReportTypeRepository;
use Illuminate\Http\Request;
use App\Repositories\HR\HrReportRepository;

class HrReportController extends Controller
{
    private $hrReportRepository;
    private $companyRepository;
    private $hrReportTypeRepository;

    public function __construct(HrReportRepository $hrReportRepository, CompanyRepository $companyRepository, HrReportTypeRepository $hrReportTypeRepository)
    {
        $this->hrReportRepository = $hrReportRepository;
        $this->companyRepository = $companyRepository;
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


    public function report_view()
    {
        return view('pages.hr.report_view')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }


    public function insert(Request $request)
    {
        return $this->hrReportRepository->insert($request);
    }

    public function list(Request $request)
    {
        return $this->hrReportRepository->list($request);
    }


    public function getReportByReportDayandCopmanyID($report_day, $copmany_id)
    {
        return $this->hrReportRepository->getReportByReportDayandCopmanyID($report_day, $copmany_id);
    }


    public function ImportExel($report_day, $copmany_id)
    {
        $copmany = $this->companyRepository->getAll();
        $companies = [];
        foreach ($copmany as $key => $value) {
            $companies[$copmany[$key]['id']] = $copmany[$key]['name'];
        }
        $report_type_array = $this->hrReportTypeRepository->list();
        $report_type = [];

        $report = $this->hrReportRepository->getReportByReportDayandCopmanyID($report_day, $copmany_id);
        $report_render = [];

        foreach ($report_type_array as $key => $value) {
            $report_type[$report_type_array[$key]['id']] = $report_type_array[$key];
            foreach ($report as $key_report => $value) {

                if($report_type_array[$key]['id'] == $report[$key_report]['report_type_id']) {

                    $report_render[$report_type_array[$key]['id']][] = $this->getInput($report[$key_report], $companies);
                } else {
                    $report_render[$report_type_array[$key]['id']][] = 'Нет';

                }

            }
        }

//        dd($report_render);
        return view('pages.hr.export')->with(
            [
                'report' => $report_render,
                'companies' => $companies,
                'report_type' => $report_type
            ]
        );
    }

    public function getInput($el, $companies)
    {

        if ($el['report_type_id'] == 1) {
            return "{$companies[$el['company_id']]}
Наименование проверяющего органа: {$el['text_1']}
Основание проверки: {$el['text_2']}
Дата начала: {$el['date_1']}
Результат проверки: {$el['text_3']}";
        };

        if ($el['report_type_id'] == 2) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Уровень и должность руководителя: {$el['text_1']}" . PHP_EOL . "
Информация о наличии тяжелой болезни или причину смерти: {$el['text_2']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 3) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Должность руководителя: {$el['text_1']}" . PHP_EOL . "
дата назначения: {$el['date_1']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 4) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Должность руководителя: {$el['text_1']}" . PHP_EOL . "
Дата освобождения от должности: {$el['date_1']}" . PHP_EOL . "
Причина освобождения от должности: {$el['text_2']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 5) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Дата травмы: {$el['date_1']}" . PHP_EOL . "
Предполагаемые причины: {$el['text_1']}" . PHP_EOL . "
Степень тяжести: {$el['text_2']}" . PHP_EOL . "
Должность сотрудника: {$el['text_3']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 6) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Судебное решение: {$el['text_1']}" . PHP_EOL . "
Должность сотрудника: {$el['text_2']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 7) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Краткие результаты: {$el['text_1']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 8) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Должность руководителя: {$el['text_1']}" . PHP_EOL . "
Причина привлечения к дисциплинарной ответственности: {$el['text_2']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 9) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Дата: {$el['date_1']} -  {$el['date_2']}" . PHP_EOL . "
Сумма задолженности: {$el['text_1']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 10) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
График работы: {$el['text_1']}" . PHP_EOL . "
Комментарий: {$el['text_2']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 11) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Должность: {$el['text_1']}" . PHP_EOL . "";
        };

        if ($el['report_type_id'] == 12) {
            return "{$companies[$el['company_id']]}" . PHP_EOL . "
Вопросы, которые требуют решения на уровне УК: {$el['text_1']}" . PHP_EOL . "";
        };

        return 'нет';


    }


}


