{{-- Show the inputs --}}
@foreach ($fields as $field)
    @if($field['name'] === 'residentialAddress')
        <div class="form-group col-12 col-md-12">
            <label>Residential Address: </label> <span class="help-block">(optional)</span>
        </div>
    @endif

    @if($field['name'] === 'permanentAddress')
        <div class="form-group col-12 col-md-12">
            <label>Permanent Address: </label> <span class="help-block">(optional)</span>
        </div>
    @endif

    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
    
@endforeach

