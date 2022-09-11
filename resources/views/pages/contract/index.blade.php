@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Контракты</h3>
        <p class="mb-2">Реестр контрактов, заключенных предприятиями холдинговой компании «Росэлектроника». В данный реестр добавляются перспективные проекты, завершенные подписанием договоров и переданные на исполнение.</p>
        <contractmodalcreate
            :user_id="{{$user['id']}}"
        ></contractmodalcreate>
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 20px;">

            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true">Все контракты</a>
            </li>
        </ul>

            <div class="tab-content border border-top-0 p-3 overflow-auto" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <contracts ref="contractslist"
                        :user_id="{{$user['id']}}"
                    ></contracts>
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
