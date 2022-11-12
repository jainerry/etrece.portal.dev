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
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('building-profile') }}"><i class="nav-icon la la-list"></i> Buildings</a></li>
        @endcan
        @can('view-faas-machineries')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-machinery') }}"><i class="nav-icon la la-list"></i> Machineries</a></li>
        @endcan
        @can('view-faas-lands')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-land') }}"><i class="nav-icon la la-list"></i> Lands</a></li>
        @endcan
        @can('view-faas-idle-lands')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-land-idle') }}"><i class="nav-icon la la-list"></i> Idle Lands</a></li>
        @endcan
        @can('view-faas-others')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-other') }}"><i class="nav-icon la la-list"></i> Others</a></li>
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
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
        @endcan
        @can('view-roles')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
        @endcan
        @can('view-permissions')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
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
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office-location') }}"><i class="nav-icon la la-building"></i> Office Locations</a></li>
        @endcan
        @can('view-offices')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office') }}"><i class="nav-icon la la-building"></i> Offices</a></li>
        @endcan
        @can('view-departments')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('department') }}"><i class="nav-icon la la-list"></i> Departments</a></li>
        @endcan
        @can('view-sections')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('section') }}"><i class="nav-icon la la-list"></i> Sections</a></li>
        @endcan
        @can('view-positions')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('position') }}"><i class="nav-icon la la-list"></i> Positions</a></li>
        @endcan
        @can('view-appointment-statuses')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('appointment') }}"><i class="nav-icon la la-list"></i> Appointment Statuses</a></li>
        @endcan
        @can('view-provinces')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('province') }}"><i class="nav-icon la la-list"></i> Provinces</a></li>
        @endcan
        @can('view-municipalities')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('municipality') }}"><i class="nav-icon la la-list"></i> Municipalities</a></li>
        @endcan
        @can('view-barangays')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('barangay') }}"><i class="nav-icon la la-list"></i> Barangays</a></li>
        @endcan
        @can('view-streets')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('street') }}"><i class="nav-icon la la-list"></i> Streets</a></li>
        @endcan
    </ul>
</li>
@endcan

@can('view-office-locations')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>FAAS Configurations</a>
        <ul class="nav-dropdown-items">
        @can('view-structural-types')
        <li class="nav-item "><a class="nav-link" href="{{ backpack_url('structural-type') }}"><i class="nav-icon la la-list"></i> Structural Types</a></li>
        @endcan
        @can('view-ind-of-buildings')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('kind-of-building') }}"><i class="nav-icon la la-list"></i> Kind of Buildings</a></li>   
        @endcan
        @can('view-structural-roofs')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('structural-roofs') }}"><i class="nav-icon la la-list"></i> Structural Roofs</a></li>
        @endcan
        @can('view-faas-assessment-statuses')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-assessment-status') }}"><i class="nav-icon la la-list"></i> Assessment Statuses</a></li>
        @endcan
        @can('view-faas-classifications')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-land-classification') }}"><i class="nav-icon la la-list"></i> Land Classifications</a></li>
        @endcan

        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('structural-flooring') }}"><i class="nav-icon la la-list"></i> Structural Floorings</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('structural-walling') }}"><i class="nav-icon la la-list"></i> Structural Wallings</a></li>
    </ul>
</li>
@endcan

<!-- RPT -->
@can('rpt-view-assessment-requests')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> Real Property Tax</a>
    <ul class="nav-dropdown-items">
        @can('rpt-create-new-assessment-request')
        <li class="nav-item"><a class="nav-link" href="{{ route('rpt-new-assessment-request') }}"><i class="nav-icon la la-list"></i> <span>New Assessment Request</span></a></li>
        @endcan
        @can('rpt-view-assessment-requests')
        <li class="nav-item"><a class="nav-link" href="{{ route('rpt-assessment-requests') }}"><i class="nav-icon la la-list"></i> <span>Assessment Requests</span></a></li>
        @endcan
    </ul>
</li>
@endcan

