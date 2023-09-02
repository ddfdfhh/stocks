@php

    $last_uri = request()->segment(2);

    $routes_arr = ['raw_materials','drivers','vehicles','products','settings','suppliers','customers','roles','states','cities', 'permissions', 'users', 'categories'];
@endphp

<ul class="menu-inner py-1">
    <!-- Dashboards -->
    <li class="menu-item @if ($last_uri == 'dashboard') active @endif">
        <a href="{{ route('admin.dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Dashboards">Dashboard</div>
        </a>

    </li>
    <li class="menu-item @if (in_array($last_uri, $routes_arr)) open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div data-i18n="Authentications">Master</div>
        </a>

        <ul class="menu-sub ">
       {{--     @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_roles'))
                <li class="menu-item @if ($last_uri == 'roles') active @endif">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                       
                        <div data-i18n="Calendar">Manage Roles</div>
                    </a>
                </li>
            @endif
            
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_permissions'))
                <li class="menu-item @if ($last_uri == 'permissions') active @endif">
                    <a href="{{ route('permissions.index') }}" class="menu-link">
                      
                        <div data-i18n="Calendar">Manage Permissions</div>
                    </a>
                </li>
            @endif
--}}
            <li class="menu-item @if ($last_uri == 'categories') active @endif">
                <a href="{{ route('categories.index') }}" class="menu-link">
                   
                    <div data-i18n="Calendar">Manage Categories</div>
                </a>
            </li>
            <li class="menu-item @if($last_uri=='products') active  @endif">
              <a href="{{route('products.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Products</div>
              </a>
            </li>
            @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_settings'))
            <li class="menu-item @if($last_uri=='settings') active  @endif">
              <a href="{{route('settings.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Settings</div>
              </a>
            </li>
     @endif
            @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_states'))
            <li class="menu-item @if($last_uri=='states') active  @endif">
              <a href="{{route('states.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage States</div>
              </a>
            </li>
     @endif
     @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_cities'))
            <li class="menu-item @if($last_uri=='cities') active  @endif">
              <a href="{{route('cities.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Cities</div>
              </a>
            </li>
     @endif
     @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_customers'))
            <li class="menu-item @if($last_uri=='customers') active  @endif">
              <a href="{{route('customers.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Customers</div>
              </a>
            </li>
     @endif
     
@if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_suppliers'))
            <li class="menu-item @if($last_uri=='suppliers') active  @endif">
              <a href="{{route('suppliers.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Suppliers</div>
              </a>
            </li>
     @endif
     @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_demo_table'))
            <li class="menu-item @if($last_uri=='demo_table') active  @endif">
              <a href="{{route('demo_tables.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Demo  Table</div>
              </a>
            </li>
     @endif
     @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_vehicles'))
            <li class="menu-item @if($last_uri=='vehicles') active  @endif">
              <a href="{{route('vehicles.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Vehicles</div>
              </a>
            </li>
     @endif
     @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_drivers'))
            <li class="menu-item @if($last_uri=='drivers') active  @endif">
              <a href="{{route('drivers.index')}}" class="menu-link">
               
                <div data-i18n="Calendar">Manage Drivers</div>
              </a>
            </li>
     @endif
        </ul>
    </li>


    <!-- User interface -->

</ul>

