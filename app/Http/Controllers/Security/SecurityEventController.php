<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Repositories\Security\SecurityEventRepository;
use Illuminate\Http\Request;

class SecurityEventController extends Controller
{


    private $securityEventRepository;

    public function __construct(SecurityEventRepository $securityEventRepository)
    {
        $this->securityEventRepository = $securityEventRepository;
    }

    public function list(Request $request)
    {
        return $this->securityEventRepository->list($request);
    }
}
