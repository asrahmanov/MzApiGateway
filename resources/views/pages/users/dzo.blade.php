@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">Ответственные за заполнение форм</h3>
    <div class="example" id="app2">
        <div class="row">
            <User-dzo :company_id="1"></User-dzo>

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
