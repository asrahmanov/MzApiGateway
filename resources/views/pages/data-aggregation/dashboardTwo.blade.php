@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">Контроль выручки АО "НПП Пульсар"</h3>
    <div class="example" id="app2">
        <div class="row">
            <data-aggregation-dashboard-two />
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')


@endpush
