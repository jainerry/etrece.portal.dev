@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    'Assessment Requests' => route('rpt-assessment-requests'),
    'Assessment Requests' => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">Assessment Requests</span>
	  </h2>
	</section>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 bold-labels">
        <div class="tab-container mb-2 ">
            <div class="nav-tabs-custom " id="form_tabs">
                <ul class="nav nav-tabs " role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="#tab_buildings" aria-controls="tab_buildings" role="tab" tab_name="buildings" data-toggle="tab" class="nav-link active" aria-selected="true">Buildings</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#tab_machineries" aria-controls="tab_machineries" role="tab" tab_name="machineries" data-toggle="tab" class="nav-link ">Machineries</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#tab_lands" aria-controls="tab_lands" role="tab" tab_name="lands" data-toggle="tab" class="nav-link ">Lands</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#tab_idle_lands" aria-controls="tab_idle_lands" role="tab" tab_name="idle_lands" data-toggle="tab" class="nav-link" aria-selected="false">Idle Lands</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#tab_others" aria-controls="tab_others" role="tab" tab_name="others" data-toggle="tab" class="nav-link" aria-selected="false">Others</a>
                    </li>
                </ul>

                <div class="tab-content p-0 ">
                    <div role="tabpanel" class="tab-pane active" id="tab_buildings">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Buildings</h5>
                                <table id='buildingsTable' class='bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline collapsed has-hidden-columns' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab_machineries">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Machineries</h5>
                                <table id='machineriesTable' class='bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline collapsed has-hidden-columns' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab_lands">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Lands</h5>
                                <table id='landsTable' class='bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline collapsed has-hidden-columns' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab_idle_lands">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Idle Lands</h5>
                                <table id='idleLandsTable' class='bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline collapsed has-hidden-columns' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab_others">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Others</h5>
                                <table id='othersTable' class='bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline collapsed has-hidden-columns' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Reference No.</th>
                                            <th>Owner</th>
                                            <th>Property Location</th>
                                            <th>Assessment Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
	</div>
</div>

@endsection

@section('after_styles')
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
@endsection

@section('after_scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(function(){
            $('#buildingsTable').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax":{
                    "url": "{{ route('rpt-buildings-search') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "arpNo" },
                    { "data": "primaryOwner" },
                    { "data": "ownerAddress" },
                    { "data": "assessmentStatus" },
                    { "data": "created_at" },
                    { "data": "options" }
                ]
            });
            $('#machineriesTable').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax":{
                    "url": "{{ route('rpt-machineries-search') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "ARPNo" },
                    { "data": "primaryOwner" },
                    { "data": "ownerAddress" },
                    { "data": "assessmentStatus" },
                    { "data": "created_at" },
                    { "data": "options" }
                ]
            });
            $('#landsTable').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax":{
                    "url": "{{ route('rpt-lands-search') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "ARPNo" },
                    { "data": "primaryOwner" },
                    { "data": "ownerAddress" },
                    { "data": "assessmentStatus" },
                    { "data": "created_at" },
                    { "data": "options" }
                ]
            });
            $('#idleLandsTable').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax":{
                    "url": "{{ route('rpt-idle-lands-search') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "ARPNo" },
                    { "data": "primaryOwner" },
                    { "data": "ownerAddress" },
                    { "data": "assessmentStatus" },
                    { "data": "created_at" },
                    { "data": "options" }
                ]
            });
            $('#othersTable').DataTable({
                "serverSide": true,
                "processing": true,
                "ajax":{
                    "url": "{{ route('rpt-others-search') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "ARPNo" },
                    { "data": "primaryOwner" },
                    { "data": "ownerAddress" },
                    { "data": "assessmentStatus" },
                    { "data": "created_at" },
                    { "data": "options" }
                ]
            });
        })
    </script>
@endsection