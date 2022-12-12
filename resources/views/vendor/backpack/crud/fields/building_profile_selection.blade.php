{{-- Building Profile Selection --}}
@php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    
    $old_value = old_empty_or_null($field['name'], false) ??  $field['value'] ?? $field['default'] ?? false;

    // by default set ajax query delay to 500ms
    // this is the time we wait before send the query to the search endpoint, after the user as stopped typing.
    $field['delay'] = $field['delay'] ?? 500;
    $field['allows_null'] = $field['allows_null'] ?? $crud->model::isColumnNullable($field['name']);
    $field['placeholder'] = $field['placeholder'] ?? 'Select Building Profile';
    $field['attribute'] = $field['attribute'] ?? $connected_entity->identifiableAttribute();
    $field['minimum_input_length'] = $field['minimum_input_length'] ?? 2;
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    <select
        name="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitBuildingProfileSelectionInputElement"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-column-nullable="{{ var_export($field['allows_null']) }}"
        data-dependencies="{{ isset($field['dependencies'])?json_encode(Arr::wrap($field['dependencies'])): json_encode([]) }}"
        data-placeholder="{{ $field['placeholder'] }}"
        data-minimum-input-length="{{ $field['minimum_input_length'] }}"
        data-data-source="{{ $field['data_source'] }}"
        data-method="{{ $field['method'] ?? 'GET' }}"
        data-field-attribute="{{ $field['attribute'] }}"
        data-connected-entity-key-name="{{ $connected_entity_key_name }}"
        data-include-all-form-fields="{{ isset($field['include_all_form_fields']) ? ($field['include_all_form_fields'] ? 'true' : 'false') : 'false' }}"
        data-ajax-delay="{{ $field['delay'] }}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control'])
        >
        
        @if ($old_value)
      
            @php
                if(!is_object($old_value)) {
                    $item = $connected_entity->find($old_value);
                }else{
                    $item = $old_value;
                }

            @endphp
            @if ($item)
            {{-- allow clear --}}
            @if ($field['allows_null']))
            <option value="" selected>
                {{ $field['placeholder'] }}
            </option>
            @endif

            <option value="{{ $item->id }}" selected>
                {{ $item->refID}}
            </option>
            @endif
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
@push('crud_fields_styles')
    {{-- include select2 css --}}
    @loadOnce('packages/select2/dist/css/select2.min.css')
    @loadOnce('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')
    {{-- allow clear --}}
    @if($field['allows_null'])
        @loadOnce('select2_from_ajax_custom_css')
        <style type="text/css">
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
    @endif
@endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    {{-- include select2 js --}}
    @loadOnce('packages/select2/dist/js/select2.full.min.js')
    @if (app()->getLocale() !== 'en')
        @loadOnce('packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
@endpush

{{-- include field specific select2 js --}}
@push('crud_fields_scripts')
@loadOnce('bpFieldInitBuildingProfileSelectionInputElement')
<script>
    function bpFieldInitBuildingProfileSelectionInputElement(element) {
        var form = element.closest('form');
        var $placeholder = element.attr('data-placeholder');
        var $minimumInputLength = element.attr('data-minimum-input-length');
        var $dataSource = element.attr('data-data-source');
        var $method = element.attr('data-method');
        var $fieldAttribute = element.attr('data-field-attribute');
        var $connectedEntityKeyName = element.attr('data-connected-entity-key-name');
        var $includeAllFormFields = element.attr('data-include-all-form-fields')=='false' ? false : true;
        var $allowClear = element.attr('data-column-nullable') == 'true' ? true : false;
        var $dependencies = JSON.parse(element.attr('data-dependencies'));
        var $ajaxDelay = element.attr('data-ajax-delay');
        var $isFieldInline = element.data('field-is-inline');
        var $fieldCleanName = element.attr('data-repeatable-input-name') ?? element.attr('name');

        // do not initialise select2s that have already been initialised
        if ($(element).hasClass("select2-hidden-accessible"))
        {
            return;
        }
        function formatState(state) {
            let stateText = state.text
            stateText = stateText.trim()
            stateText = stateText.replaceAll("'", "\\'")
            if($(stateText).find('.building_reference_id').length > 0 ){
                return $(stateText).find('.building_reference_id').html();
            }else{
                return stateText;
            }
        };
        //init the element
        $(element).select2({
            theme: 'bootstrap',
            multiple: false,
            placeholder: 'Select Building Profile',
            minimumInputLength: 2,
            allowClear: true,
            templateSelection: formatState,
            dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body),
            ajax: {
                url: $dataSource,
                type: $method,
                dataType: 'json',
                delay: $ajaxDelay,
                data: function (params) {
                    if ($includeAllFormFields) {
                        return {
                            q: params.term, // search term
                            page: params.page, // pagination
                            form: form.serializeArray(), // all other form inputs
                            triggeredBy: 
                            {
                                'rowNumber': element.attr('data-row-number') !== 'undefined' ? element.attr('data-row-number')-1 : false, 
                                'fieldName': $fieldCleanName
                            }
                        };
                    } else {
                        return {
                            q: params.term, // search term
                            page: params.page, // pagination
                        };
                    }
                },
                processResults: function (data, params) {
                    let paginate = false;
                    return {
                        results: $.map(data, function(item) {

                            let customText = ''

                            let primaryOwner = ''

                            if(item.ownerType === 'NameProfile') {
                                primaryOwner = item.name_profile.full_name
                            }
                            else {
                                primaryOwner = item.citizen_profile.full_name
                            }
                            
                            customText = `
                                <div>
                                    <div>
                                        Reference ID: <b class="building_reference_id"> ${item.refID}</b>
                                    </div>
                                    <div>
                                        Land Profile Reference ID: <b class="building_reference_id"> ${item.faas_land_profile.refID}</b>
                                    </div>
                                    <div>
                                        Primary Owner: <b class="building_reference_id"> ${primaryOwner}</b>
                                    </div>
                                    <div>
                                        Kind of Building: <b class="building_reference_id"> ${item.building_classification.name}</b>
                                    </div>
                                    <div>
                                        Building Age: <b class="building_reference_id"> ${item.buildingAge}</b>
                                    </div>
                                    <div>
                                        Structural Type: <b class="building_reference_id"> ${item.structural_type.name}</b>
                                    </div>
                                    <div>
                                        Address: <b class="building_reference_id"> ${item.ownerAddress}</b>
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
        });

        // if any dependencies have been declared
        // when one of those dependencies changes value
        // reset the select2 value
        for (var i=0; i < $dependencies.length; i++) {
            var $dependency = $dependencies[i];
            //if element does not have a custom-selector attribute we use the name attribute
            if(typeof element.attr('data-custom-selector') == 'undefined') {
                form.find('[name="'+$dependency+'"], [name="'+$dependency+'[]"]').change(function(el) {
                        $(element.find('option:not([value=""])')).remove();
                        element.val(null).trigger("change");
                });
            }else{
                // we get the row number and custom selector from where element is called
                let rowNumber = element.attr('data-row-number');
                let selector = element.attr('data-custom-selector');

                // replace in the custom selector string the corresponding row and dependency name to match
                selector = selector
                    .replaceAll('%DEPENDENCY%', $dependency)
                    .replaceAll('%ROW%', rowNumber);

                $(selector).change(function (el) {
                    $(element.find('option:not([value=""])')).remove();
                    element.val(null).trigger("change");
                });
            }
        }
    }

    if (typeof processItemText !== 'function') {
        function processItemText(item, $fieldAttribute) {
            var $appLang = '{{ app()->getLocale() }}';
            var $appLangFallback = '{{ Lang::getFallback() }}';
            var $emptyTranslation = '{{ trans("backpack::crud.empty_translations") }}';
            var $itemField = item[$fieldAttribute];

            // try to retreive the item in app language; then fallback language; then first entry; if nothing found empty translation string
            return typeof $itemField === 'object' && $itemField !== null
                ? $itemField[$appLang] ? $itemField[$appLang] : $itemField[$appLangFallback] ? $itemField[$appLangFallback] : Object.values($itemField)[0] ? Object.values($itemField)[0] : $emptyTranslation
                : $itemField;
        }
    }
    </script>
    @endLoadOnce
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
