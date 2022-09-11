@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">Отчет по заболевшим просмотр</h3>
    <p class="mb-2">Описание раздела</p>
    <div class="example" id="app2">
        <div class="row">
            <hr_report_covid_view  :user_id="{{$user['id']}}"></hr_report_covid_view>
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
