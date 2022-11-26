{{-- secondary_owner_field field --}}
@php
    $connected_entity = new $field['model']();
    $connected_entity_key_name = $connected_entity->getKeyName();
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
    $field['delay'] = $field['delay'] ?? 500;
    $field['attribute'] = $field['attribute'] ?? $connected_entity->identifiableAttribute();
    $old_value = old_empty_or_null($field['name'], false) ?? ($field['value'] ?? ($field['default'] ?? false));
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')
<input type="hidden" name="{{ $field['name'] }}" value="" @if (in_array('disabled', $field['attributes'] ?? [])) disabled @endif />
<select name="{{ $field['name'] }}[]" id="" style="width: 100%" data-init-function="bpFieldInitDummyFieldElement"
    data-data-source="{{ $field['data_source'] }}" data-ajax-delay="{{ $field['delay'] }}"
    data-field-is-inline="{{ var_export($inlineCreate ?? false) }}"
    data-connected-entity-key-name="{{ $connected_entity_key_name }}"
    data-dependencies="{{ isset($field['dependencies']) ? json_encode(Arr::wrap($field['dependencies'])) : json_encode([]) }}"
    @include('crud::fields.inc.attributes', ['default_class' => 'form-control']) class="js-data-example-ajax col-lg-12" multiple>

    @if ($old_value)
        @foreach ($old_value as $item)
            @if (!is_object($item))
                @php
                    $item = $connected_entity->find($item);
                @endphp
            @endif
            <option value="{{ $item->id }}" selected>
                {{ $item->full_name }}
            </option>
        @endforeach
    @endif
</select>

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
        .secondary_owner_field_class {
            display: none;
        }
        .select2-selection__clear::after {
            content: ' Clear';
        }
        .select2-results__option {
            padding: 10px !important;
            border: 1px solid #e2e5ec;
            border-radius: 5px;
            margin: 10px;
        }
        .select2-container--bootstrap .select2-results>.select2-results__options {
            max-height: 500px;
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
                function formatState(state) {
                    let stateText = state.text
                    stateText = stateText.trim()
                    stateText = stateText.replaceAll("'", "\\'")
                    if($(stateText).find('.fullname').length > 0 ){
                        return $(stateText).find('.fullname').html();
                    }else{
                        return stateText;
                    }
                };

                var $dataSource = element.attr('data-data-source');
                var $isFieldInline = element.data('field-is-inline');
                var $dependencies = JSON.parse(element.attr('data-dependencies'));
                $(element).select2({
                    theme: 'bootstrap',
                    multiple: true,
                    placeholder: 'Select Secondary Owner',
                    minimumInputLength: 2,
                    allowClear: true,
                    templateSelection: formatState,
                    
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
                            return {
                                results: $.map(data, function(item) {
                                    
                                    let customText = `
                                            <div>
                                                <div>
                                                    Fullname: <b class="fullname"> ${item.fullname}</b>
                                                </div>
                                                <div>
                                                    Owner Type: <b class="fullname"> Citizen Profile</b>
                                                </div>
                                                <div>
                                                    Reference ID: <b> ${item.refID}</b>
                                                </div>
                                                <div>
                                                    Birth Date: <b> ${item.bdate}</b>
                                                </div>
                                                <div>
                                                    Barangay: <b> ${item.barangay.name}</b>
                                                </div>
                                                <div>
                                                    Address: <b> ${item.address}</b>
                                                </div>
                                            </div>
                                        `
                                    let searchResults = { text: customText, id: item.id }
                                    return searchResults

                                }),
                                pagination: {
                                    more: paginate,
                                }

                            }
                        },
                        cache: true
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                })
                // if any dependencies have been declared
                // when one of those dependencies changes value
                // reset the select2 value
                for (var i = 0; i < $dependencies.length; i++) {
                    var $dependency = $dependencies[i];
                    //if element does not have a custom-selector attribute we use the name attribute
                    if (typeof element.attr('data-custom-selector') == 'undefined') {
                        form.find('[name="' + $dependency + '"], [name="' + $dependency + '[]"]').change(function(el) {
                            $(element.find('option:not([value=""])')).remove();
                            element.val(null).trigger("change");
                        });
                    } else {
                        // we get the row number and custom selector from where element is called
                        let rowNumber = element.attr('data-row-number');
                        let selector = element.attr('data-custom-selector');
                        // replace in the custom selector string the corresponding row and dependency name to match
                        selector = selector
                            .replaceAll('%DEPENDENCY%', $dependency)
                            .replaceAll('%ROW%', rowNumber);
                        $(selector).change(function(el) {
                            $(element.find('option:not([value=""])')).remove();
                            element.val(null).trigger("change");
                        });
                    }
                }
            }
        </script>
    @endLoadOnce
@endpush
