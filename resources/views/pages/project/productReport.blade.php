@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Проекты продукта</h3>

        <div class="border border-top-0 p-3 overflow-auto" >
            <div>
                <products-report
                    :user_id="{{ $user['id'] }}"
                    :product-name="'{{$name}}'"
                />
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





