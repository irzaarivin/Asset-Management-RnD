{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('asset') }}"><i class="las la-toolbox nav-icon"></i> Assets</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="las la-folder nav-icon"></i> Categories</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('borrowing') }}"><i class="las la-handshake nav-icon"></i> Borrowings</a></li>
<li class="nav-item nav-dropdown" id='Authentication' onclick="toggleDropdown('Authentication')">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="lar la-user nav-icon"></i>
        User
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('user') }}">
                <i class="las la-user-friends nav-icon"></i>
                Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('role') }}">
                <i class="las la-user-tag nav-icon"></i>
                Roles
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('permission') }}">
                <i class="las la-user-cog nav-icon"></i>
                Permissions
            </a>
        </li>
    </ul>
</li>
