@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">Отправка заданий</h3>
    <p class="mb-2"></p>
    <div id="app2">
        <div class="row">
            <worksheet></worksheet>
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
