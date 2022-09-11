@extends('layout.master2')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
@endpush
{{--@php--}}
{{--dd(session()->all())--}}
{{--@endphp--}}
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
{{--          <div class="col-md-4 pr-md-0">--}}
{{--            <div class="auth-left-wrapper" style="background-image: url({{ url('https://via.placeholder.com/219x452') }})">--}}

{{--            </div>--}}
{{--              <div class="auth-left-wrapper" style="--}}
{{--                  background-color: #efefef;--}}
{{--                  background-image: url({{ asset('assets/images/login.jpg') }});--}}
{{--                  background-repeat: no-repeat;--}}
{{--                  background-size: contain;--}}
{{--                  ">--}}

{{--              </div>--}}
{{--          </div>--}}

            <div class="col-md-8">

            </div>
                <div class="col-md-12 stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <a href="#" class="noble-ui-logo d-block mb-2">{{env('LOGO_NAME')}}<span> {{env('LOGO_POSTFIX')}}</span></a>
                            <h5 class="text-muted font-weight-normal mb-4">Create account.</h5>
                            <form id="wizardVerticalRegistration" class="forms-sample" action="{{route('auth.registration')}}" method="post">
                                @csrf
                                <h2>Customer Information</h2>
                                <section class="row preloader" >
                                    <div class="col-md-12 pl-md-0">
                                        <div class="auth-form-wrapper px-4 py-5">
                                            @error('message')
                                            <div class="alert alert-icon-danger" role="alert">
                                                <i data-feather="alert-circle"></i>
                                                {{$errors->first()}}
                                            </div>
                                            @enderror

                                                <div class="form-group">
                                                    <label for="first_name">First name</label>
                                                    <input name="first_name" type="text" class="form-control" id="first_name"  required placeholder="Adam" value="{{ old('first_name') }}">
                                                    @error('first_name')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name">Last name</label>
                                                    <input name="last_name" type="text" class="form-control" id="last_name"  required placeholder="Sendler" value="{{ old('last_name') }}">
                                                    @error('last_name')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="company">Company name</label>
                                                    <input name="company" type="text" class="form-control" id="company" required placeholder="Netflix" value="{{ old('company') }}">
                                                    @error('company')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="job_title">Job title</label>
                                                    <input name="job_title" type="text" class="form-control" id="job_title" required placeholder="Actor" value="{{ old('job_title') }}">
                                                    @error('job_title')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input name="email" type="email" class="form-control" id="email" required placeholder="examle@email.com" value="{{ old('email') }}">
                                                    @error('email')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input name="phone" type="text" class="form-control" id="phone" required placeholder="+78955454545" value="{{ old('phone') }}">
                                                    @error('phone')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input name="password" type="password" class="form-control" required id="password" placeholder="Password">
                                                    @error('password')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <input name="confirm_password" type="password" class="form-control" required id="confirm_password"  placeholder="Confirm Password">
                                                    @error('confirm_password')
                                                    <div class="alert-icon-danger text-center" role="alert">
                                                        <i data-feather="alert-circle"></i>
                                                        {{$errors->first()}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="role_id">Role</label>
                                                    <select name="role_id" class="form-control" id="role_id">
                                                        <option value="2">scientist</option>
                                                        <option value="3">procurement</option>
                                                        <option value="4">project manager</option>
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                </section>

                                <h2>Areas of interest</h2>
                                <section class="row">
                                    <div class="col-md-12 pl-md-0">
                                        <div class="auth-form-wrapper px-4 py-5">
                                            @for($i=1;$i<=3;$i++)
                                                <div class="form-group">
                                                    <label for="role_id">Area of interest {{$i}}</label>
                                                    <select name="area_of_interest_{{$i}}" class="form-control" id="area_of_interest_{{$i}}">
                                                        <option value="0">Не выбранно</option>
                                                        @foreach($diseases as $item)
                                                            <option value="{{$item->id}}">{{$item->disease_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </section>

{{--                                <h2>Contract</h2>--}}
{{--                                <section>--}}
{{--                                    <h4>Heading</h4>--}}
{{--                                    <p>Morbi ornare tellus at elit ultrices id dignissim lorem elementum. Sed eget nisl at justo condimentum dapibus. Fusce eros justo,--}}
{{--                                        pellentesque non euismod ac, rutrum sed quam. Ut non mi tortor. Vestibulum eleifend varius ullamcorper. Aliquam erat volutpat.--}}
{{--                                        Donec diam massa, porta vel dictum sit amet, iaculis ac massa. Sed elementum dui commodo lectus sollicitudin in auctor mauris--}}
{{--                                        venenatis.</p>--}}
{{--                                </section>--}}

                                <h2>Forth Step</h2>
                                <section>
                                    <div class="mt-3">
                                        <button type="submit"  class="btn btn-primary mr-2 mb-2 mb-md-0">Sing up</button>
                                        {{--                  <a href="{{ url('/') }}" class="btn btn-primary mr-2 mb-2 mb-md-0">Sing up</a>--}}
                                        {{--                  <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">--}}
                                        {{--                    <i class="btn-icon-prepend" data-feather="twitter"></i>--}}
                                        {{--                    Sign up with twitter--}}
                                        {{--                  </button>--}}
                                    </div>
                                </section>
                            </form>
                            <a href="{{ url('/auth/login') }}" class="d-block mt-3 text-muted">Already a user? Sign in</a>
                        </div>
                    </div>
                </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
@endpush

@push('custom-scripts')

    <script>

        $.ajaxSetup({async:false});
        let registrationForm = $("#wizardVerticalRegistration")

        registrationForm.steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            stepsOrientation: 'vertical',
            onStepChanging: function (event, currentIndex, newIndex)
            {
                registrationForm.find('.content').css('border','none')
                registrationForm.find('.alert-registration').remove()
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex)
                {
                    return true;
                }

                // Forbid next action on "Warning" step if the user is to young
                if (currentIndex < newIndex && newIndex === 1)
                {
                    let noError = false
                    $.post("{{route('register_validator')}}", registrationForm.serialize())
                        .done(response=> {
                            noError = true
                        })
                        .fail(error=> {
                            console.log( "error",error.responseJSON );
                            errorMessage(JSON.parse(error.responseJSON).errors)
                            registrationForm.find('.content').css('border','1px solid red')
                        })
                        .always(response=> {

                        })
                    return noError

                }

                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex)
                {
                    return true;
                }
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                console.log(event,'/', currentIndex,'/', priorIndex)
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                {
                    registrationForm.steps("next");
                }
                // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                if (currentIndex === 2 && priorIndex === 3)
                {
                    registrationForm.steps("previous");
                }
            },
            onFinishing: function (event, currentIndex)
            {
                registrationForm.validate().settings.ignore = ":disabled";
                return registrationForm.valid();
            },
            onFinished: function (event, currentIndex)
            {
                alert("Submitted!");
            }
        });

        function errorMessage(errors){
            for (let item in errors){
                registrationForm.find(`#${item}`).after(`<div class="alert-registration alert-icon-danger text-center" role="alert">
                            <i data-feather="alert-circle"></i>
                                ${errors[item][0]}
                            </div>`)
            }
        }
    </script>
@endpush
