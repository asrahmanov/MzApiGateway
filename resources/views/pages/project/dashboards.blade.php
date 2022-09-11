@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <div id="app2">
        <h3 class="page-title" style="margin-bottom: 20px;">Дашборды</h3>
        <p class="mb-2">Дашборд — информационная панель, отображающая внесенные в систему данные в режиме реального времени. Воронка продаж позволяет оперативно отслеживать жизненный цикл перспективных проектов и своевременно получать информацию о процессе контрактации на дочерних зависимых обществах (ДЗО) холдинговой компании «Росэлектроника». Отчетные данные доступны в форме столбцовой и круговой диаграмм.</p>
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 20px;">
            <li class="nav-item">
                <a class="nav-link active" id="histogram-tab" data-toggle="tab" href="#histogram" role="tab" aria-controls="histogram"
                   aria-selected="true">Текущий статус проектов</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="diagram-tab" data-toggle="tab" href="#diagram" role="tab" aria-controls="diagram"
                   aria-selected="false">Воронка продаж</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="movement-tab" data-toggle="tab" href="#movement" role="tab" aria-controls="movement"
                   aria-selected="false">Движение проектов за неделю</a>
            </li>
        </ul>
        <div class="tab-content border border-top-0 p-3" id="myTabContent">
{{--            <div class="row">--}}
{{--                <div class="col-6">--}}
{{--                    <select id="company_filter" disabled>--}}
{{--                        <option value="0">Все</option>--}}
{{--                        @foreach ($companies as $company)--}}
{{--                            <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                <label for="report_max_date" class="col-form-label">Дата по:</label>--}}
{{--                <div class="col-4 grid-margin"><input type="date" class="form-control datepicker" id="report_max_date"></div>--}}
{{--            </div>--}}
            <div class="tab-pane fade show active" id="histogram" role="tabpanel" aria-labelledby="histogram-tab">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Текущий статус проектов</h6>
                            <dash-board-current
                                :user_id="{{ $user['id'] }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="diagram" role="tabpanel" aria-labelledby="diagram-tab">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Воронка продаж</h6>
                            <dash-board-sales
                                :user_id="{{ $user['id'] }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="movement" role="tabpanel" aria-labelledby="movement-tab">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Движение проектов за неделю</h6>
                            <dash-board-movement
                                :user_id="{{ $user['id'] }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>


@endpush




@push('custom-scripts')

@endpush
