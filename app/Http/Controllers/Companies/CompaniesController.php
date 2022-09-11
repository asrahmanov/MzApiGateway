<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use function GuzzleHttp\json_encode;

class CompaniesController extends Controller
{
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }


    public function index()
    {
        return view('pages.companies.index')->with(
            [
                'companies' => $this->companyRepository->all(),
                'user' => session()->get('user')
            ]
        );
    }

    // Компании одного юзера
    public function getByUser($user_id)
    {
        return Response::json($this->companyRepository->getByUser($user_id));
    }


    // Показать одну компанию
    public function show($id)
    {
        $result = $this->companyRepository->one($id);

        if (!is_null($result)) {
            return view('pages.companies.company')->with([
                'user' => session()->get('user'),
                'company' => $result
            ]);
        } else {
            return redirect(route('error.404'), 404);
        }


    }

    // Забрать данные по одной компании
    public function get($id)
    {
        return Response::json($this->companyRepository->one($id));
    }

    public function update(Request $request)
    {
        return $this->companyRepository->update($request);
    }


    public function insert(Request $request)
    {
        return $this->companyRepository->insert($request);
    }


    public function parentList(Request $request)
    {
        return $this->companyRepository->parentList($request);
    }


}
