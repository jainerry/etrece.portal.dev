{{-- Show the inputs --}}
@php
 $reRender = [];
@endphp
@foreach ($fields as $index=> $field)

@if( ($field['name'] == "line_of_business") || ($field['name'] == "number_of_employees" ) ||  ($field['name'] == "vehicles"))
    @php
    array_push($reRender,$field);
    @endphp
@else

    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
@endif

@endforeach
{{-- @dd($fields['line_of_business'],$fields['number_of_employee']) --}}

<div class="tab-container mb-2 col-12 order-last">
    <div class="nav-tabs-custom " id="form_tabs2">
        <ul class="nav nav-tabs " role="tablist">
            <li role="presentation" class="nav-item">
                <a href="#line-of-business" aria-controls="line-of-business" role="tab" tab_name="line-of-business" data-toggle="tab"
                    class="nav-link active">Line Of Businesses (multiple)</a>
            </li>
            <li role="presentation" class="nav-item">
                <a href="#number-of-employees" aria-controls="number_of_employees" role="tab" tab_name="number-of-employees" data-toggle="tab"
                    class="nav-link ">Number of Employee</a>
            </li>
            <li role="presentation" class="nav-item">
                <a href="#vehicles" aria-controls="number-of-employee" role="tab" tab_name="vehicles" data-toggle="tab"
                    class="nav-link ">Vehicle</a>
            </li>
        </ul>
        {{-- @dd($crud->c($fields['line_of_business']['type'], $fields['line_of_business']['view_namespace'] ?? false), $fields['line_of_business'])) --}}
        <div class="tab-content p-0 ">
        

            @foreach($reRender as $index=>$field)
            
            <div class="tab-pane {{ $index == 0 ? "active":""}}" id="{{STR::slug($field['name'])}}">
                @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
               
            </div>
            @endforeach
            
        </div>
    </div>
</div>
