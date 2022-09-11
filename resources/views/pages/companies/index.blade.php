@extends('layout.master')


@section('content')
    <h3 class="page-title mb-3">ДЗО</h3>
    <p class="mb-2">Справочник дочерних зависимых обществ (ДЗО) холдинговой компании «Росэлектроника», по которым доступно получение актуальных данных о перспективных проектах и заключенных контрактах, аналитических сведений и общей информации.</p>
    <div class="example">
        <div class="row">

            @foreach($companies as $company)
                <div class="col-12 col-md-12 col-xl-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                           <div style="display: flex; justify-content: space-between;">
                               <div style="display: flex; justify-content: center; align-items: center"><h5 class="card-title" style="    font-size: 16px">{{$company['name']}}</h5></div>
                           <div> <a href="{{route('companies.one',['id'=>$company['id']])}}" class="btn btn-primary">Посмотреть</a></div>
                           </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
