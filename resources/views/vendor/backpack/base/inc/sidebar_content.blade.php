{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@can('Citizens')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('citizen-profile') }}"><i class="nav-icon la la-list"></i> Citizens</a></li>
@endcan

@can('Employees')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('employee') }}"><i class="nav-icon la la-users"></i> Employees</a></li>
@endcan

@can('FAAS Masterlist')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> FAAS Masterlist</a>
    <ul class="nav-dropdown-items">
        @can('FAAS Masterlist > Lands')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-land') }}"><i class="nav-icon la la-ellipsis-v"></i> Lands</a></li>
        @endcan
        @can('FAAS Masterlist > Buildings')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('building-profile') }}"><i class="nav-icon la la-ellipsis-v"></i> Buildings</a></li>
        @endcan
        @can('FAAS Masterlist > Machineries')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-machinery') }}"><i class="nav-icon la la-ellipsis-v"></i> Machineries</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('Authentication')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
    <ul class="nav-dropdown-items">
        @can('Authentication > Users')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('user') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Users</span></a></li>
        @endcan
        @can('Authentication > Roles')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('role') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Roles</span></a></li>
        @endcan
        @can('Authentication > Permissions')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-ellipsis-v"></i> <span>Permissions</span></a></li>
        @endcan
    </ul>
</li>
@endcan


@can('Configurations')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i> Configurations</a>
    <ul class="nav-dropdown-items">

        <!-- Temporary Disabled -->
        {{-- 
            @can('Configurations > Office Locations')
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office-location') }}"><i class="nav-icon la la-building"></i> Office Locations</a></li>
            @endcan
            @can('Configurations > Offices')
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office') }}"><i class="nav-icon la la-building"></i> Offices</a></li>
            @endcan
            @can('Configurations > Sections')
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('section') }}"><i class="nav-icon la la-list"></i> Sections</a></li>
            @endcan
            @can('Configurations > Positions')
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('position') }}"><i class="nav-icon la la-list"></i> Positions</a></li>
            @endcan
            @can('Configurations > Appointment Statuses')
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('appointment') }}"><i class="nav-icon la la-list"></i> Appointment Statuses</a></li>
            @endcan
        --}}

        @can('Configurations > Regions')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('regions') }}"><i class="nav-icon la la-ellipsis-v"></i> Regions</a></li>
        @endcan
        @can('Configurations > Provinces')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('province') }}"><i class="nav-icon la la-ellipsis-v"></i> Provinces</a></li>
        @endcan
        @can('Configurations > Cities')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('cities') }}"><i class="nav-icon la la-ellipsis-v"></i> Cities</a></li>
        @endcan
        @can('Configurations > Barangays')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('barangay') }}"><i class="nav-icon la la-ellipsis-v"></i> Barangays</a></li>
        @endcan
        @can('Configurations > Streets')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('street') }}"><i class="nav-icon la la-ellipsis-v"></i> Streets</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('FAAS Configurations')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>FAAS Configurations</a>
        <ul class="nav-dropdown-items">
        @can('FAAS Configurations > Structural Types')
        <li class="nav-item "><a class="nav-link d-flex" href="{{ backpack_url('structural-type') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Types</a></li>
        @endcan

        <!-- Temporary Disabled -->
        {{-- 
            @can('FAAS Configurations > Kind of Buildings')
            <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('kind-of-building') }}"><i class="nav-icon la la-ellipsis-v"></i> Kind of Buildings</a></li>   
            @endcan
        --}}

        @can('FAAS Configurations > Structural Roofs')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('structural-roofs') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Roofs</a></li>
        @endcan
        @can('FAAS Configurations > Land Classifications')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('faas-land-classification') }}"><i class="nav-icon la la-ellipsis-v"></i> Land Classifications</a></li>
        @endcan
        @can('FAAS Configurations > Building Classifications')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('faas-building-classifications') }}"><i class="nav-icon la la-ellipsis-v"></i> Building Classifications</a></li>
        @endcan
        @can('FAAS Configurations > Machinery Classifications')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('faas-machinery-classifications') }}"><i class="nav-icon la la-ellipsis-v"></i> Machinery Classifications</a></li>
        @endcan
        @can('FAAS Configurations > Structural Floorings')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('structural-flooring') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Floorings</a></li>
        @endcan
        @can('FAAS Configurations >Structural Wallings')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('structural-walling') }}"><i class="nav-icon la la-ellipsis-v"></i> Structural Wallings</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('Treasury Configurations')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>Treasury Configurations</a>
    <ul class="nav-dropdown-items"> 
        @can('Treasury Configurations > CTC Types')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('ctc-type') }}"><i class="nav-icon la la-ellipsis-v"></i> CTC Types</a></li>
        @endcan
        @can('Treasury Configurations > RPT Rates')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('rpt-rates') }}"><i class="nav-icon la la-ellipsis-v"></i> RPT Rates</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('RPT Assessments')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> RPT Assessments</a>
    <ul class="nav-dropdown-items">
        @can('RPT Assessments > Lands')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('rpt-lands') }}"><i class="nav-icon la la-ellipsis-v"></i> Lands</a></li>
        @endcan
        @can('RPT Assessments > Buildings')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('rpt-buildings') }}"><i class="nav-icon la la-ellipsis-v"></i> Buildings</a></li>
        @endcan
        @can('RPT Assessments > Machineries')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('rpt-machineries') }}"><i class="nav-icon la la-ellipsis-v"></i> Machineries</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('Treasury')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> Treasury</a>
    <ul class="nav-dropdown-items">
        @can('Treasury > RPT')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('treasury-rpt') }}"><i class="nav-icon la la-ellipsis-v"></i> RPT</a></li>
        @endcan
        @can('Treasury > Business')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('treasury-business') }}"><i class="nav-icon la la-ellipsis-v"></i> Business</a></li>
        @endcan
        @can('Treasury > CTC')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('treasury-ctc') }}"><i class="nav-icon la la-ellipsis-v"></i> CTC</a></li>
        @endcan
        @can('Treasury > Other')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('treasury-other') }}"><i class="nav-icon la la-ellipsis-v"></i> Other</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('Chart of Accounts')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> Chart of Accounts</a>
    <ul class="nav-dropdown-items">
        @can('Chart of Accounts > Level 1')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('chart-of-account-lvl1') }}"><i class="nav-icon la la-ellipsis-v"></i> Level 1</a></li>
        @endcan
        @can('Chart of Accounts > Level 2')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('chart-of-account-lvl2') }}"><i class="nav-icon la la-ellipsis-v"></i> Level 2</a></li>
        @endcan
        @can('Chart of Accounts > Level 3')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('chart-of-account-lvl3') }}"><i class="nav-icon la la-ellipsis-v"></i> Level 3</a></li>
        @endcan
        @can('Chart of Accounts > Level 4')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('chart-of-account-lvl4') }}"><i class="nav-icon la la-ellipsis-v"></i> Level 4</a></li>
        @endcan
    </ul>
</li>
@endcan


@can('Business')
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-briefcase"></i> Business</a>
    <ul class="nav-dropdown-items">
        @can('Business > Business Profile')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('business-profiles') }}"><i class="nav-icon la la-ellipsis-v "></i>Business Profile</a></li>
        @endcan
        @can('Business > Name Profiles')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('name-profiles') }}"><i class="nav-icon la la la-ellipsis-v"></i> Name profiles</a></li>
        @endcan
        @can('Business > Business Vehicles')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('business-vehicles') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Vehicles</a></li>
        @endcan
        @can('Business > Business Types')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('business-type') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Types</a></li>
        @endcan
        @can('Business > Business Activities')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('business-activity') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Activities</a></li>
        @endcan
        @can('Business > Business Categories')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('business-category') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Categories</a></li>
        @endcan
        @can('Business > Business Fees')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('business-fees') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Fees</a></li>
        @endcan
        @can('Business > Business Tax Fees')
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('business-tax-fees') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Tax Fees</a></li>
        @endcan
        @can('Business > Business Tax Assessments')
        <li class="nav-item"><a class="nav-link d-flex" href="{{ backpack_url('buss-tax-assessments') }}"><i class="nav-icon la la-ellipsis-v"></i> Business Tax Assessments</a></li>
        @endcan
    </ul>
</li>
@endcan

@can('Transaction Logs')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('transaction-logs') }}"><i class="nav-icon la la-clipboard"></i> Transaction Logs</a></li>
@endcan


