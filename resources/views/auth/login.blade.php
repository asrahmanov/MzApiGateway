@extends('layout.master2')
@section('content')
<div class="page-content d-flex align-items-center justify-content-center" id="app2">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-5 pr-md-0 p-4">
            <div class="auth-left-wrapper">

            </div>
          </div>
          <div class="col-md-7 pl-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2">{{env('LOGO_NAME')}}<span> {{env('LOGO_POSTFIX')}}</span></a>
              <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.</h5>
                <auth></auth>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
