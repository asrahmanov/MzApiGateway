@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">Запросы на исполнение </h3>
    <p class="mb-2">Пожалуйста заполните формы</p>
    <div class="example" id="app2">
        <div class="row">
            <interview-view-one :edit="'true'" :id="{{ $id }}"  :user_id="{{$user['id']}}"></interview-view-one>
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
