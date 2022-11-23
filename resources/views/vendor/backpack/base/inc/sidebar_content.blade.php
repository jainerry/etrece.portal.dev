{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@can('view-citizen-profiles')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('citizen-profile') }}"><i class="nav-icon la la-list"></i> Citizens</a></li>
@endcan

@can('view-employees')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('employee') }}"><i class="nav-icon la la-users"></i> Employees</a></li>
@endcan

@can('view-building-profiles')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> FAAS Masterlist</a>
    <ul class="nav-dropdown-items">
        @can('view-building-profiles')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('building-profile') }}"><i class="nav-icon la la-ellipsis-v"></i> Buildings</a></li>
        @endcan
        @can('view-faas-machineries')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-machinery') }}"><i class="nav-icon la la-ellipsis-v"></i> Machineries</a></li>
        @endcan
        @can('view-faas-lands')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-land') }}"><i class="nav-icon la la-ellipsis-v"></i> Lands</a></li>
        @endcan
    </ul>
</li>
@endcan

<!-- Users, Roles, Permissions -->
@can('view-users')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
    <ul class="nav-dropdown-items">
        @can('view-users')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('user') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Users</span></a></li>
        @endcan
        @can('view-roles')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('role') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Roles</span></a></li>
        @endcan
        @can('view-permissions')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Permissions</span></a></li>
        @endcan
    </ul>
</li>
@endcan

<!-- Configurations -->
@can('view-office-locations')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i> Configurations</a>
    <ul class="nav-dropdown-items">
        @can('view-office-locations')
        {{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office-location') }}"><i class="nav-icon la la-building"></i> Office Locations</a></li> --}}
        @endcan
        @can('view-offices')
        {{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office') }}"><i class="nav-icon la la-building"></i> Offices</a></li> --}}
        @endcan
        @can('view-sections')
        {{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('section') }}"><i class="nav-icon la la-list"></i> Sections</a></li> --}}
        @endcan
        @can('view-positions')
        {{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('position') }}"><i class="nav-icon la la-list"></i> Positions</a></li> --}}
        @endcan
        @can('view-appointment-statuses')
        {{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('appointment') }}"><i class="nav-icon la la-list"></i> Appointment Statuses</a></li> --}}
        @endcan
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('regions') }}"><i class="nav-icon la la-ellipsis-v"></i> Regions</a></li>
        @can('view-provinces')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('province') }}"><i class="nav-icon la la-ellipsis-v"></i> Provinces</a></li>
        @endcan
        
        @can('view-municipalities')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('cities') }}"><i class="nav-icon la la-ellipsis-v"></i> Cities</a></li>
        @endcan
        @can('view-barangays')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('barangay') }}"><i class="nav-icon la la-ellipsis-v"></i> Barangays</a></li>
        @endcan
        @can('view-streets')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('street') }}"><i class="nav-icon la la-ellipsis-v"></i> Streets</a></li>
        @endcan
    </ul>
</li>
@endcan

@can('view-office-locations')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>FAAS Configurations</a>
        <ul class="nav-dropdown-items">
        @can('view-structural-types')
        <li class="nav-item "><a class="nav-link d-flex" href="{{ backpack_url('structural-type') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Types</a></li>
        @endcan
        {{-- @can('view-kind-of-buildings')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('kind-of-building') }}"><i class="nav-icon la la-ellipsis-v"></i> Kind of Buildings</a></li>   
        @endcan --}}
        @can('view-structural-roofs')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('structural-roofs') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Roofs</a></li>
        @endcan
        @can('view-land-classifications')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('faas-land-classification') }}"><i class="nav-icon la la-ellipsis-v"></i> Land Classifications</a></li>
        @endcan
        @can('view-building-classifications')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('faas-building-classifications') }}"><i class="nav-icon la la-ellipsis-v"></i> Building Classifications</a></li>
        @endcan
        @can('view-machinery-classifications')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('faas-machinery-classifications') }}"><i class="nav-icon la la-ellipsis-v"></i> Machinery Classifications</a></li>
        @endcan
        @can('view-structural-floorings')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('structural-flooring') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Floorings</a></li>
        @endcan
        @can('view-structural-wallings')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('structural-walling') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Wallings</a></li>
        @endcan
        @can('view-structural-additional-items')
        
        @endcan
    </ul>
</li>
@endcan

<!-- RPT -->
@can('rpt-view-assessment-requests')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> Real Property Tax</a>
    <ul class="nav-dropdown-items">
        @can('rpt-create-new-assessment-request')
        <li class="nav-item d-flex"><a class="nav-link d-flex" href="{{ route('rpt-new-assessment-request') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>New Assessment Request</span></a></li>
        @endcan
        @can('rpt-view-assessment-requests')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ route('rpt-assessment-requests') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Assessment Requests</span></a></li>
        @endcan
    </ul>
</li>
@endcan

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-briefcase"></i> Business</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('business-profiles') }}"><i class="nav-icon la la-ellipsis-v "></i>Business Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('name-profiles') }}"><i class="nav-icon la la la-ellipsis-v"></i> Name profiles</a></li>
    </ul>
</li>
@can('view-transaction-logs')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('transaction-logs') }}"><i class="nav-icon la la-clipboard"></i> Transaction logs</a></li>
@endcan


