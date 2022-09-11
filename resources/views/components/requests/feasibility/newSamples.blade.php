@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            font-size: 0.725rem!important;
            color: white;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff!important;
        }
    </style>

@endpush

@php
$title = 'Samples';
$tabs_name = 'samples';
$parent_post_name='diagnos';
$post_name = 'samples';
$entities = $samples;
$entity_id_field = 'id';
$entity_name_field = 'biospecimen_type';
$count_js_var_name = 'countSample'
@endphp

            <ul class="nav nav-tabs" id="{{$tabs_name}}Tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="{{$tabs_name}}_tab{{$count}}" data-toggle="tab" href="#{{$tabs_name}}{{$count}}" role="tab" aria-controls="{{$tabs_name}}{{$count}}" aria-selected="true">{{$tabs_name}}{{$count}}</a>
                </li>
                <li id="new_tab_{{$tabs_name}}" class="nav-item">
                    <a  class="nav-link disabled" data-toggle="tab" href="#" role="tab" aria-controls="disabled" aria-selected="false">+</a>
                </li>
            </ul>
            <div class="tab-content border border-top-0 p-3" id="{{$tabs_name}}TabsContent">
                <div class="tab-pane fade show active" id="{{$tabs_name}}{{$count}}" role="tabpanel" aria-labelledby="{{$tabs_name}}{{$count}}-tab">
                    <div class="form-group">
                        <label>{{$title}}</label>
                        <select class="js-example-basic-multiple w-100"  id="{{$post_name}}{{$count}}" name="{{$parent_post_name}}[{{$count}}][{{$post_name}}]">
                            <option value="_">Select</option>
                            @foreach($entities as $item)
                                <option value="{{ $item->{$entity_id_field} }}">{{ $item->{$entity_name_field} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="specific_requirements_to_the_sample{{$count}}">Specific requirements to the sample</label>
                        <input id="specific_requirements_to_the_sample{{$count}}" class="form-control" disabled name="{{$parent_post_name}}[{{$count}}][{{$post_name}}][specific_requirements_to_the_sample]" type="text">
                    </div>
                </div>
            </div>

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
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

    $('#{{$post_name}}{{$count}}').on("select2:select",function(e){
        if($('#{{$post_name}}{{$count}}').val() !== '_'){
            $('#specific_requirements_to_the_sample{{$count}}').removeAttr('disabled')
        }else {
            $('#specific_requirements_to_the_sample{{$count}}').attr('disabled','')
        }
    });

    let {{$count_js_var_name}} = {{$count + 1}}

    $('#new_tab_{{$tabs_name}}').click(e=>{
        const counter = {{$count_js_var_name}}

        $('#{{$tabs_name}}Tabs .nav-link').removeClass('active')
        $('#{{$tabs_name}}TabsContent .tab-pane').removeClass(['active','show'])
        let tab =`
        <li class="nav-item">
        <a class="nav-link active" id="{{$tabs_name}}_tab${counter}" data-toggle="tab" href="#{{$tabs_name}}${counter}" role="tab" aria-controls="{{$tabs_name}}${counter}" aria-selected="true">{{$tabs_name}}${counter}</a>
        </li>`
        let el = `
         <div class="tab-pane fade show active " id="{{$tabs_name}}${counter}" role="tabpanel" aria-labelledby="{{$tabs_name}}${counter}-tab">
                                <div class="form-group">
                                    <label>Indication/diagnosis</label>
                                    <select class="{{$tabs_name}}-select-multiple${counter} w-100" id="{{$post_name}}${counter}" name="{{$parent_post_name}}[${counter}][{{$post_name}}][id]">
                                    <option value="_">Select</option>
                                        @foreach($entities as $item)
        <option value="{{ $item->{$entity_id_field} }}">{{ $item->{$entity_name_field} }}</option>
                                        @endforeach
        </select>
    </div>
     <div class="form-group">
        <label for="specific_requirements_to_the_sample${counter}">Specific requirements to the sample</label>
        <input id="specific_requirements_to_the_sample${counter}" class="form-control" disabled name="{{$parent_post_name}}[${counter}][{{$post_name}}][specific_requirements_to_the_sample]" type="text">
    </div>
    <button id="delete-{{$tabs_name}}-tab${counter}" type="button" class="btn btn-danger">Delete tab</button>
</div>
`
        $('#new_tab_{{$tabs_name}}').before(tab)
        $('#{{$tabs_name}}TabsContent').append(el)
        $(`.samples-select-multiple${counter}`).select2();
        $(`.{{$tabs_name}}-select-multiple${counter}`).select2();


        $(`#{{$post_name}}${counter}`).on("select2:select",function(e){
            if($(`#{{$post_name}}${counter}`).val() !== '_'){
                $(`#specific_requirements_to_the_sample${counter}`).removeAttr('disabled')
            }else {
                $(`#specific_requirements_to_the_sample${counter}`).attr('disabled','')
            }
        });

        $(`#delete-{{$tabs_name}}-tab${counter}`).click(e=>{
            $(`#{{$tabs_name}}_tab${counter}`).remove()
            $(`#{{$tabs_name}}${counter}`).remove()
        })

        {{ $count_js_var_name }}++
    })

</script>
@endpush
