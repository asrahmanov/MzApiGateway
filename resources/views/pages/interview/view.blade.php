@extends('layout.master')


@section('content')
    <h5>{{ $user['last_name'] }} {{ $user['first_name'] }}</h5>
    <h5>{{ $user['company'][0]['name'] }} </h5>
    <h3 class="page-title mb-3">Запросы на исполнение</h3>
    <p class="mb-2">Пожалуйста, заполните формы</p>
    <div class="example" id="app2">
        <div class="row">
            <interview-view :status="'new'" :user_id="{{$user['id']}}"></interview-view>
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
