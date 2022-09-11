@extends('layout.master')

@section('content')
    <h2>Необходимо заполнить информацию</h2>
  <div  id="app2">
      <user-one
          user_id="{{ $user_id }}"
      ></user-one>
  </div>
@endsection

