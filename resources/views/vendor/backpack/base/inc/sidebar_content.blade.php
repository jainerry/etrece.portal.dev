{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('citizen-profile') }}"><i class="nav-icon la la-list"></i> Citizen Profiles</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('employee') }}"><i class="nav-icon la la-users"></i> Employee Profiles</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> FAAS Masterlist</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('building-profile') }}"><i class="nav-icon la la-list"></i> Building Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-machinery') }}"><i class="nav-icon la la-list"></i> Machineries Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('faas-other') }}"><i class="nav-icon la la-list"></i> Others</a></li>
    </ul>
</li>

<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
    </ul>
</li>

<!-- Configurations -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i> Configurations</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office-location') }}"><i class="nav-icon la la-building"></i> Office locations</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('office') }}"><i class="nav-icon la la-building"></i> Offices</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('department') }}"><i class="nav-icon la la-list"></i> Departments</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('section') }}"><i class="nav-icon la la-list"></i> Sections</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('position') }}"><i class="nav-icon la la-list"></i> Positions</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('appointment') }}"><i class="nav-icon la la-list"></i> Appointment Statuses</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('province') }}"><i class="nav-icon la la-list"></i> Provinces</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('municipality') }}"><i class="nav-icon la la-list"></i> Municipalities</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('barangay') }}"><i class="nav-icon la la-list"></i> Barangays</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('street') }}"><i class="nav-icon la la-list"></i> Streets</a></li>
    </ul>
</li>



