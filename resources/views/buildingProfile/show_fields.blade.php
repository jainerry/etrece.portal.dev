{{-- Show the inputs --}}
@foreach ($fields as $field)
    @if($field['name']=='second_owner')
    <div class="form-group col-sm-12 search_secondary">
        <label for="">Secondary Owner</label>
        <input type="text" class="form-control" name="search">
    </div>
    @else
    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
    @endif
@endforeach

