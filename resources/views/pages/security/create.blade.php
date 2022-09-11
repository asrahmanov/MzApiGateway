@extends('layout.master')



@section('content')
    <div id="app2">
        <h2 class="mb-1">Создание отчета безопасности</h2>
        <add-event :user_id="{{$user['id']}}"></add-event>
    </div>
@endsection





