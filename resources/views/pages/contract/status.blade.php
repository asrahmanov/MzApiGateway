@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Статусы контрактов</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    Наименование
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($status as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                </tr>
            @endforeach
            </tbody>

        </table>
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
