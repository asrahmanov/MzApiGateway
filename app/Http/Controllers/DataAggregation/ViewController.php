<?php

namespace App\Http\Controllers\DataAggregation;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{

    public function dashboardOne()
    {
        return view('pages.data-aggregation.dashboardOne')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }
    public function dashboardTwo()
    {
        return view('pages.data-aggregation.dashboardTwo')->with(
            [
                'user' => session()->get('user')
            ]
        );
    }

}


