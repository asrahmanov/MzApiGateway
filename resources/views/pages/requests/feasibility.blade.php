@extends('layout.master')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />


@endpush
@section('content')
    <h1 class="page-title">Feasibility request</h1>
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Direct input of the request into the system</h4>
                <form class="cmxform" id="feasibility_request_form" method="post" action="{{route('request.feasibility-request')}}">
                    @csrf
                    <fieldset>
                        <div id="app2">
                                <diagnos :diseases="{{json_encode(array_slice($diseases,0,2),JSON_UNESCAPED_UNICODE) }}" :samples="{{json_encode(array_slice($samples,0,2),JSON_UNESCAPED_UNICODE) }}"></diagnos>
                        </div>
{{--                        @include('components.requests.feasibility.diagnos')--}}
                        <div class="form-group mt-5">
                            <label for="name">Inclusion criteria</label>
                            <input id="name" class="form-control" name="inclusion_criteria" type="text">
                        </div>
                        <div class="form-group">
                            <label for="exclusion_criteria">Exclusion criteria</label>
                            <input id="exclusion_criteria" class="form-control" name="exclusion_criteria" type="text">
                        </div>
                        <div class="form-group">
                            <label for="donor_requirements">Donor requirements</label>
                            <input id="donor_requirements" class="form-control" name="donor_requirements" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Additional tests</label>
                            <input id="name" class="form-control" name="additional_tests" type="text">
                        </div>
                        <div class="form-group">
                            <label for="preferable_timelines">Preferable timelines(if any)</label>
                            <input id="preferable_timelines" class="form-control" name="preferable_timelines" type="text">
                        </div>
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manual request registration</h4>
                <form class="cmxform" id="feasibility_request_form" method="post" action="{{route('request.feasibility-request')}}">
                    @csrf
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <textarea
                                            class="form-control"
                                            name="manual_feasibility_request"
                                            id="tinymceExample"
                                            rows="10"
                                            placeholder="Please copy text of your request here. The rest to be done by platform operator"
                                            required
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</div>


@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/typeahead-js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
{{--    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>--}}
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/js/tags-input.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>

    <script src="{{ asset('assets/js/timepicker.js') }}"></script>
{{--    <script src="{{ asset('assets/js/tinymce.js') }}"></script>--}}
<script>



</script>
@endpush
