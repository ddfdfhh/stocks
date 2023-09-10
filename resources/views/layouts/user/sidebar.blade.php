<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <!-- <img class="navbar-brand-full app-header-logo" src="{{ asset('img/logo.png') }}" width="65"
             alt="Infyom Logo"> -->
        <a href="{{ url('/') }}">Admin</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <!-- <img class="navbar-brand-full" src="{{ asset('img/logo.png') }}" width="45px" alt=""/> -->
            Admin
        </a>
    </div>
    <ul class="sidebar-menu">
        @include('layouts.admin.menu')
    </ul>
</aside>
