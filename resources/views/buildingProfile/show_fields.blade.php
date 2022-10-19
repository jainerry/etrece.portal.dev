{{-- Show the inputs --}}
@foreach ($fields as $field)
    @if($field['name']=='second_owner')
    
    @else
    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
    @endif
@endforeach

