@extends('layout.master')

@section('content')
    <h2>Создание пользователя </h2>
    <div id="app2">
        <user-createdzo
            user_company_id="{{ $user['company'][0]['id'] }}"
            role_id="{{ $user['role_id'] }}"
        ></user-createdzo>
    </div>
@endsection

