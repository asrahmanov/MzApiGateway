@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">Создание формы </h3>
    <div class="example" id="app2">
        <div class="row">
            <interview-form-create-one :edit="'true'"   :user_id="{{$user['id']}}"></interview-form-create-one>
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
