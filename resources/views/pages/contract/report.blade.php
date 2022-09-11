@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Отчет по контрактам </h3>

        <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 20px;">

            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true">Отчет по контрактам </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="graphic-tab" data-toggle="tab" href="#graphic" role="tab" aria-controls="graphic"
                   aria-selected="false">График по контрактам </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="graphic-tab1" data-toggle="tab" href="#graphic1" role="tab" aria-controls="graphic1"
                   aria-selected="false">График по контрактам 2</a>
            </li>

        </ul>

        <div class="tab-content border border-top-0 p-3 overflow-auto" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <contract-report
                    :user_id="{{ $user['id'] }}"
                />
            </div>

            <div class="tab-pane" id="graphic" role="tabpanel" aria-labelledby="graphic-tab">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Воронка продаж</h6>
{{--                            <div id="apexBar"></div>--}}
                            <chart   :user_id="{{ $user['id'] }}"
                                     ref="bar"
                                ></chart>


{{--                                companies='{!! json_encode($companies) !!}'--}}

                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="graphic1" role="tabpanel" aria-labelledby="graphic-tab1">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Воронка продаж 2</h6>
                            <donut
                                :user_id="{{ $user['id'] }}"
                                ref="donut"
                                ></donut>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>

@endpush





