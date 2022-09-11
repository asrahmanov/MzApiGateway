@extends('layout.master')
@push('plugin-styles')

@endpush

@section('content')
    <div id="app2">
        <red-button
            :user_id="{{ $user['id'] }}"
        ></red-button>
    </div>
@endsection

@push('plugin-scripts')

@endpush

