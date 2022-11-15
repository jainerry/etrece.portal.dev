@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    'Assessment Requests' => route('rpt-assessment-requests'),
    'Open' => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">Assessment Request</span>
        <small>View Assessment Request Details.</small>
        @can('rpt-view-assessment-requests')
            <small class=""><a href="{{ route('rpt-assessment-requests') }}" class="font-sm"><i class="la la-angle-double-left"></i> Back to all <span>Assessment Requests</span></a></small>
        @endcan
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

                    @foreach($requestData as $data)
                        @php
                            $primaryOwner = null;
                            if($data['citizen_profile']) {
                                if($data['citizen_profile']['mName']) {
                                    $primaryOwner = $data['citizen_profile']['fName']." ".$data['citizen_profile']['mName']." ".$data['citizen_profile']['lName'];
                                }
                                else {
                                    $primaryOwner = $data['citizen_profile']['fName']." ".$data['citizen_profile']['lName'];
                                }
                            }
                        @endphp
                        <div class="form-group col-12 col-md-3">
                            <b>Reference No.:</b> <span>{{ $data['ARPNo'] ?? '-' }}</span>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <b>OCT/T CT No.:</b> <span>{{ $data['octTctNo'] ?? '-' }}</span>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <b>PIN:</b> <span>{{ $data['pin'] ?? '-' }}</span>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <b>Transaction Code:</b> <span>{{ $data['transactionCode'] ?? '-' }}</span>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <b>TD No:</b> <span>{{ $data['tdNo'] ?? '-' }}</span>
                        </div>
                        
                        <div class="form-group col-12 col-md-3">
                            <b>Primary Owner:</b> <span>{{ $primaryOwner ?? '-' }}</span>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <b>TD No:</b> <span>{{ $data['transactionCode'] ?? '-' }}</span>
                        </div>
                    @endforeach

                    <div class="form-group col-12 col-md-12 required " element="div" bp-field-wrapper="true" bp-field-name="creationType" bp-field-type="select_from_array">
                        <label>Change Assessment Status To:</label>
                        <select name="assessmentStatusId" class="form-control">
                            @foreach($assessmentStatuses as $assessmentStatus)
                                <option value="{{ $assessmentStatus['id'] }}">{{ $assessmentStatus['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
            </div>

            <div id="saveActions" class="form-group">
                <input type="hidden" name="_save_action" value="save">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-success">
                        <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                        <span data-value="save">Save</span>
                    </button>
                </div>
            </div>
		</form>

	</div>
</div>

@endsection