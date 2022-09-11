@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;"></h3>
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 20px;">

            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true">Информация по проекту</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info"
                   aria-selected="true">Этапы проекты</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#report" role="tab" aria-controls="info"
                   aria-selected="true">Отчетность</a>
            </li>
        </ul>

        <div class="tab-content border border-top-0 p-3" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
           <div>
               <project-edit
                   :user_id="{{ $user['id'] }}"
                   :project_id="{{ $project_id }}"
               />
           </div>
            </div>


            <div class="tab-pane fade show" id="info" role="tabpanel" aria-labelledby="home-tab">
                <div>
                    <project-stage
                        :user_id="{{ $user['id'] }}"
                        :project_id="{{ $project_id }}"
                    />

                </div>
            </div>

            <div class="tab-pane fade show" id="report" role="tabpanel" aria-labelledby="home-tab">
                <div>
                    <report-create
                        :user_id="{{ $user['id'] }}"
                        :project_id="{{ $project_id }}"
                    ></report-create>


                    <report-list ref="reportlist"
                        :user_id="{{ $user['id'] }}"
                        :project_id="{{ $project_id }}"
                    ></report-list>



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
@endpush

@push('custom-scripts')


@endpush
