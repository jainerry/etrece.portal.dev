@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    'Create New Assessment Request' => route('rpt-new-assessment-request'),
    'Create New Assessment Request' => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">Create New Assessment Request</span>
	  </h2>
	</section>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 bold-labels">
        
        {{-- <div class="alert alert-danger pb-0 hidden">
            <ul class="list-unstyled">
                <li><i class="la la-info-circle"></i> Error Lists.</li>
            </ul>
        </div> --}}

        <form method="POST" action="">
            @csrf
            <div class="card">
                <div class="card-body row">
                    {{-- <div class="form-group col-12 col-lg-4 required text-danger" element="div" bp-field-wrapper="true" bp-field-name="fName" bp-field-type="text">
                        <label>First Name</label>
                        <input type="text" name="fName" value="" class="form-control">
                        <div class="invalid-feedback d-block">The First Name field is required.</div>
                    </div> --}} 
                    <div class="form-group col-12 col-md-6 required " element="div" bp-field-wrapper="true" bp-field-name="creationType" bp-field-type="select_from_array">
                        <label>Creation Type</label>
                        <select name="creationType" class="form-control">
                            @foreach ($creationOptions as $creationOption)
                                <option value="{{ $creationOption['value'] }}">{{ $creationOption['text'] }}</option>
                            @endforeach
                        </select>
                    </div>   
                </div> 
            </div>

            <div id="saveActions" class="form-group">
                <input type="hidden" name="_save_action" value="save_and_back">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-success">
                        <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                        <span data-value="save_and_back">Save and back</span>
                    </button>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">▼</span></button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="javascript:void(0);" data-value="save_and_edit">Save and edit this item</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-value="save_and_new">Save and new item</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-value="save_and_preview">Save and preview</a>
                        </div>
                    </div>
                </div>
                {{-- <a href="" class="btn btn-default"><span class="la la-ban"></span> &nbsp;Cancel</a> --}}
            </div>
		</form>

	</div>
</div>

@endsection