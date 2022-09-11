@extends('layout.master')



@section('content')
    <div id="app2">
        <h2 class="mb-1">Просмотр отчетов</h2>
        <view-event :user_id="{{$user['id']}}"></view-event>
    </div>
@endsection





