@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title">{{$company['name']}}</h3>

        <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 20px;">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true">Информация о компании</a>
            </li>
            @if (($user['role_id'] ?? "") === 1)
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                   aria-controls="profile" aria-selected="false">Дочернии компании</a>
            </li>
            @endif


            @if (($user['role_id'] ?? "") === 1)
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                   aria-controls="contact" aria-selected="false">Пользователи</a>
            </li>
            @endif


        </ul>
        <div class="tab-content border border-top-0 p-3" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <Companyinfo  :company_id="{{$company['id']}}"></Companyinfo>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                <Companyadd  ref="Companyadd" :company_id="{{$company['id']}}"></Companyadd>
                <div class="mt-5">

                <Companylist ref="Companylist" :company_id="{{$company['id']}}"></Companylist>
                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <Useradd  ref="userAdd" :company_id="{{$company['id']}}"></Useradd>
                <div class="mt-5">

                    <Userlist  ref="UserList" :company_id="{{$company['id']}}"></Userlist>
                </div>
            </div>
            <div class="tab-pane fade" id="disabled" role="tabpanel" aria-labelledby="disabled-tab">...</div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
@endpush

@push('custom-scripts')

@endpush
