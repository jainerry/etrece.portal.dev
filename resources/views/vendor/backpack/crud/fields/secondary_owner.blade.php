{{-- secondary_owner_field field --}}
@php
    $connected_entity = new $field['model']();
    $connected_entity_key_name = $connected_entity->getKeyName();
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
    $field['delay'] = $field['delay'] ?? 500;
    $field['minimum_input_length'] = $field['minimum_input_length'] ?? 2;
    $field['attribute'] = $field['attribute'] ?? $connected_entity->identifiableAttribute();
    $old_value = old_empty_or_null($field['name'], false) ?? ($field['value'] ?? ($field['default'] ?? false));
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

<input type="hidden" name="{{ $field['name'] }}" value="" @if (in_array('disabled', $field['attributes'] ?? [])) disabled @endif data-init-function="bpFieldInitDummyFieldElement" />

{{-- v2 of secondary_owner --}}
<div class="card">
    <div class="card-body">

        <div>
            <button class="btn btn-default btn-secondary-owner-add">Add Secondary Owner</button>
        </div>
    </div>
</div>



{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

{{-- CUSTOM CSS --}}
@push('crud_fields_styles')
    @loadOnce('packages/select2/dist/css/select2.min.css')
    @loadOnce('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')

    @loadOnce('secondary_owner_field_style')
    <style>
        .search_secondary_container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #0000006b;
            overflow-y:scroll;
        }

        .search_secondary_container .box {
            width: 700px;
            max-width: 100%;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            min-height: 350px;
            background-color: white;
            left: 0;
            right: 0;
            margin: auto;
            border-radius: 15px;
            max-height: 500px;
            overflow-y: auto;
            
        }
    </style>
    @endLoadOnce
@endpush

{{-- CUSTOM JS --}}
@push('crud_fields_scripts')
    @loadOnce('packages/select2/dist/js/select2.full.min.js')
    @if (app()->getLocale() !== 'en')
        @loadOnce('packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif

    {{-- How to add some JS to the field? --}}
    @loadOnce('bpFieldInitDummyFieldElement')
        <script>
            function bpFieldInitDummyFieldElement(element) {
                let body = $('body')

                function secondary_owner_search_template() {

                    return `<div class="search_secondary_container " style="display:none;">
                             <div class="box p-3"> 
                                <div class="container-fluid"> 
                                    <div class="row">
                                        <div class="col-6"> 
                                            <label> Search by Name or Reference No: </label> <br>
                                            <input class="form-control search-input-secondary" placeholder="Search Text...">
                                        </div>
                                        <div class=" col-12 mt-3 search-result"> 

                                        </div>
                                    </div>
                                </div>
                             </div>
                        </di>`;
                }

                function searching_template() {
                    return `<div class="text-center">
                        Searching...
                         </div>`;
                }
                $(".btn-secondary-owner-add").click(function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    let container = $('.search_secondary_container');

                    if (container.length == 0) {

                        $('body').append(secondary_owner_search_template)
                        $('.search_secondary_container').fadeIn();
                        $.ah
                    }
                })
                $("body").on('click', '.search_secondary_container', function(e) {
                    $(this).fadeOut(function(e) {
                        $(this).remove();
                    })
                })
                $("body").on('click', '.search_secondary_container .box', function(e) {
                    e.stopImmediatePropagation();
                })
                let timeOutInput = null;

                $("body").on('keyup', '.search-input-secondary', function(e) {
                    let val = $(this).val();
                    let length = val.length;
                    let resTemp = [`<ul class="p-0" style="list-style:none">`];
                    if (val == "") {
                        $('.search-result').html()
                        return;
                    }
                    clearTimeout(timeOutInput);
                    if (length >= 3) {
                        timeOutInput = setTimeout(() => {
                            $('.search-result').html(searching_template);
                            $.ajax({
                                url: '{{ $field['data_source'] }}',
                                method: 'GET',
                                data: {
                                    q: val
                                },
                                success: function(e) {
                                    $.each(e, function() {
                                        resTemp.push(`<li class="border p-3 mb-2 l" data-json="${JSON.stringify(this)}">
                                                        <div class="col-lg-9">
                                                            <div>
                                                                <input type="checkbox" class="citId pe-1 d-none" value="${this.id}">
                                                                <strong>Name: ${this.fullname} - ${this.refID} </strong>
                                                                </div>
                                                                <div>
                                                                Barangay: ${this.barangay.name}
                                                                </div>
                                                                <div>
                                                                Birthday: ${this.bdate}
                                                                </div>
                                                                <div>
                                                                Civil Status: ${this.civilStatus} 
                                                                </div>
                                                        </div>
                                                        

                                                    </li>`)
                                    })
                                    resTemp.push(`</ul>`)
                                    $('.search-result').html(resTemp.join(''))
                                }
                            })
                        }, 500);

                    }
                })
                // this function will be called on pageload, because it's
                // present as data-init-function in the HTML above; the
                // element parameter here will be the jQuery wrapped
                // element where init function was defined

                var $dataSource = element.attr('data-data-source');
                var $isFieldInline = element.data('field-is-inline');
                var $dependencies = JSON.parse(element.attr('data-dependencies'));
                var $minimumInputLength = element.attr('data-minimum-input-length');

                function formatState(el) {
                    // console.log($(.attr('data-barangay'))
                    element = `<div>
                                                <div class="pb-1"><b>${el.text}</b></div>
                                                <div> 
                                                    <div>
                                                        <b>birthdate:</b> <span> ${el.element.dataset.bdate} </span>
                                                    </div>
                                                    <div>
                                                        <b>barangay:</b> <span> ${el.element.dataset.barangay} </span>
                                                    </div>
                                                </div>
                                            </div>`;

                    return $(element);
                }


             


            }
        </script>
    @endLoadOnce
@endpush
