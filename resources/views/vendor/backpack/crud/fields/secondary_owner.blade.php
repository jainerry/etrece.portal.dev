{{-- secondary_owner_field field --}}
@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
    $field['delay'] = $field['delay'] ?? 500;
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

<select name="{{ $field['name'] }}" id="" style="width: 100%" 
data-init-function="bpFieldInitDummyFieldElement"
    data-data-source="{{ $field['data_source'] }}" 
    data-ajax-delay="{{ $field['delay'] }}"
    data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
    @include('crud::fields.inc.attributes', ['default_class' =>  'form-control'])
    class="js-data-example-ajax col-lg-12">
</select>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

{{-- CUSTOM CSS --}}
@push('crud_fields_styles')
    +
    @loadOnce('packages/select2/dist/css/select2.min.css')
    @loadOnce('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')

    @loadOnce('secondary_owner_field_style')
    <style>
        .secondary_owner_field_class {
            display: none;
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
                // this function will be called on pageload, because it's
                // present as data-init-function in the HTML above; the
                // element parameter here will be the jQuery wrapped
                // element where init function was defined
                var $dataSource = element.attr('data-data-source');
                var $isFieldInline = element.data('field-is-inline');
                console.log(element.val());
                $(element).select2({
                    theme: 'bootstrap',
                    multiple: true,
                    placeholder: 'Select Secondary Owner',
                    minimumInputLength: 1,
                    allowClear: true,
                    dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body),
                    ajax: {
                        url: '{{ $field['data_source'] }}',
                        method: 'GET',
                        delay: "{{ $field['delay'] }}",
                        data: function(params) {
                            return {
                                q: params.term
                            }
                        },
                        processResults: function(data) {
                            let paginate = false;
                            console.log({
                                results: $.map(data, function() {
                                    return {
                                        text: this.entry_data,
                                        id: this.id
                                    }
                                }),
                                pagination: {
                                    more: paginate,
                                }
                               
                            })
                            return {
                                results: $.map(data, function(item) {
                                    console.log(item.fName)
                                    return {
                                        text: `<div>
                                                <div class="pb-1"><b>${item.fName} ${item.mName} ${item.lName}</b></div>
                                                <div> 
                                                    <div>
                                                        <b>birthdate:</b> <span> ${item.bdate} </span>
                                                    </div>
                                                    <div>
                                                        <b>barangay:</b> <span> ${item.barangay.name} </span>
                                                    </div>
                                                </div>
                                            </div>`,
                                        id: item.id
                                    }
                                }),
                                pagination: {
                                    more: paginate,
                                }
                               
                            }
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, 

                })
            }
        </script>
    @endLoadOnce
@endpush
