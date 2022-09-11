@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Table</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                            <tr>
                                <th>Date of creation</th>
                                <th>Internal number</th>
                                <th>External number</th>
                                <th>Project name</th>
                                <th>Status</th>
                                <th>Responsible</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $item)
                                <tr>
                                    <td><a href="{{route('order.one',['id'=>$item->proj_id,'company_id'=>$company_id])}}">{{$item->fr_date}}</a></td>
                                    <td><a href="{{route('order.one',['id'=>$item->proj_id,'company_id'=>$company_id])}}">{{$item->internal_id}}</a></td>
                                    <td><a href="{{route('order.one',['id'=>$item->proj_id,'company_id'=>$company_id])}}">{{$item->external_id}}</a></td>
                                    <td><a href="{{route('order.one',['id'=>$item->proj_id,'company_id'=>$company_id])}}">{{$item->project_name}}</a></td>
                                    <td><a href="{{route('order.one',['id'=>$item->proj_id,'company_id'=>$company_id])}}">{{$item->client_status->en}}</a></td>
                                    <td><a href="{{route('order.one',['id'=>$item->proj_id,'company_id'=>$company_id])}}">{{$item->staf}}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
