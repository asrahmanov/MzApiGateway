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
        <div class="form-group">
            <label>Samples</label>
            <select class="js-example-basic-multiple w-100" id="samples1" disabled multiple="multiple" name="diagnos[1][samples][]">
                @foreach($samples as $item)
                    @if($item->deleted == 0)
                        <option value="{{$item->id}}">{{$item->biospecimen_type}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="specific_requirements_to_the_sample1">Specific requirements to the sample</label>
            <input id="specific_requirements_to_the_sample1" disabled class="form-control" name="diagnos[1][specific_requirements_to_the_sample]" type="text">
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

    $('#disease1').on("select2:select",function(e){
        if($('#disease1').val() !== '_'){
            $('#samples1').removeAttr('disabled')
            $('#specific_requirements_to_the_sample1').removeAttr('disabled')
        }else {
            $('#samples1').attr('disabled','')
            $('#specific_requirements_to_the_sample1').attr('disabled','')
        }
    });

    let count = 2

    $('#new_tab_diagnosis').click(e=>{
        const counter = count

        $('#diagnosTabs .nav-link').removeClass('active')
        $('#diagnosTabsContent .tab-pane').removeClass(['active','show'])
        let tab =`
        <li class="nav-item">
        <a class="nav-link active" id="diagnos_tab${counter}" data-toggle="tab" href="#diagnos${counter}" role="tab" aria-controls="diagnos${counter}" aria-selected="true">diagnos${counter}</a>
        </li>`
        let el = `
         <div class="tab-pane fade show active " id="diagnos${counter}" role="tabpanel" aria-labelledby="diagnos${counter}-tab">
                                <div class="form-group">
                                    <label>Indication/diagnosis</label>
                                    <select class="diagnos-select-multiple${counter} w-100" id="disease${counter}" name="diagnos[${counter}][disease]">
                                    <option value="_">Select</option>
                                        @foreach($diseases as $item)
        <option value="{{$item->id}}">{{$item->disease_name}}</option>
                                        @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Samples</label>
        <select class="samples-select-multiple${counter} w-100" id="samples${counter}" disabled multiple="multiple" name="diagnos[${counter}][samples][]">
             @foreach($samples as $item)
        @if($item->deleted == 0)
        <option value="{{$item->id}}">{{$item->biospecimen_type}}</option>
        @endif
        @endforeach
        </select>
    </div>
     <div class="form-group">
        <label for="specific_requirements_to_the_sample${counter}">Specific requirements to the sample</label>
        <input id="specific_requirements_to_the_sample${counter}" class="form-control" disabled name="diagnos[${counter}][specific_requirements_to_the_sample]" type="text">
    </div>
    <button id="delete-diagnos-tab${counter}" type="button" class="btn btn-danger">Delete tab</button>
</div>
`
        $('#new_tab_diagnosis').before(tab)
        $('#diagnosTabsContent').append(el)
        $(`.samples-select-multiple${counter}`).select2();
        $(`.diagnos-select-multiple${counter}`).select2();


        $(`#disease${counter}`).on("select2:select",function(e){
            if($(`#disease${counter}`).val() !== '_'){
                $(`#samples${counter}`).removeAttr('disabled')
                $(`#specific_requirements_to_the_sample${counter}`).removeAttr('disabled')
            }else {
                $(`#samples${counter}`).attr('disabled','')
                $(`#specific_requirements_to_the_sample${counter}`).attr('disabled','')
            }
        });

        $(`#delete-diagnos-tab${counter}`).click(e=>{
            $(`#diagnos_tab${counter}`).remove()
            $(`#diagnos${counter}`).remove()
        })

        count++
    })

</script>
@endpush
