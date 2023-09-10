@php
    
    $last_uri = request()->segment(2);
    
    $routes_arr = ['receive_payments', 'create_order', 'generated_product_stocks', 'create_material_stocks', 'units', 'input_materials', 'drivers', 'vehicles', 'products', 'settings', 'suppliers', 'customers', 'roles', 'states', 'cities', 'permissions', 'users', 'categories'];
    $raw_arr = ['create_material_stocks', 'units', 'input_materials'];
    $product_arr = ['generated_product_stocks', 'products'];
    $leads_arr = ['lead_sources', 'leads'];
    $spend_arr = ['spendable_items', 'expenses'];
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
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_users'))
                <li class="menu-item @if ($last_uri == 'users') active @endif">
                    <a href="{{ route('users.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Users</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole(['Admin']) ||
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

            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_states'))
                <li class="menu-item @if ($last_uri == 'states') active @endif">
                    <a href="{{ route('states.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage States</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_cities'))
                <li class="menu-item @if ($last_uri == 'cities') active @endif">
                    <a href="{{ route('cities.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Cities</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>


    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_customers'))
        <li class="menu-item @if ($last_uri == 'customers') active @endif">
            <a href="{{ route('customers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div data-i18n="Calendar">Manage Customers</div>
            </a>
        </li>
    @endif
    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_suppliers'))
        <li class="menu-item @if ($last_uri == 'suppliers') active @endif">
            <a href="{{ route('suppliers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cabinet"></i>
                <div data-i18n="Calendar">Manage Suppliers</div>
            </a>
        </li>
    @endif
    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_vehicles'))
        <li class="menu-item @if ($last_uri == 'vehicles') active @endif">
            <a href="{{ route('vehicles.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-car"></i>
                <div data-i18n="Calendar">Manage Vehicles</div>
            </a>
        </li>
    @endif
    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_drivers'))
        <li class="menu-item @if ($last_uri == 'drivers') active @endif">
            <a href="{{ route('drivers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Calendar">Manage Drivers</div>
            </a>
        </li>
    @endif
    <li class="menu-item @if (in_array($last_uri, $raw_arr)) open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cylinder"></i>
            <div data-i18n="Authentications">Manage Raw Materials</div>
        </a>

        <ul class="menu-sub ">
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_input_materials'))
                <li class="menu-item @if ($last_uri == 'input_materials') active @endif">
                    <a href="{{ route('input_materials.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Input Materials</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_units'))
                <li class="menu-item @if ($last_uri == 'units') active @endif">
                    <a href="{{ route('units.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Units</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_create_material_stocks'))
                <li class="menu-item @if ($last_uri == 'create_material_stocks') active @endif">
                    <a href="{{ route('create_material_stocks.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Material Stocks</div>
                    </a>
                </li>
            @endif

        </ul>
    </li>
    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_create_orders'))
        <li class="menu-item @if ($last_uri == 'create_orders') active @endif">
            <a href="{{ route('create_orders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-diamond"></i>
                <div data-i18n="Calendar">Manage Orders</div>
            </a>
        </li>
    @endif
    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_receive_payments'))
        <li class="menu-item @if ($last_uri == 'receive_payments') active @endif">
            <a href="{{ route('receive_payments.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-diamond"></i>
                <div data-i18n="Calendar">Receive Payments</div>
            </a>
        </li>
    @endif
    <li class="menu-item @if (in_array($last_uri, $product_arr)) open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cuboid"></i>
            <div data-i18n="Authentications">Manage O/P Product</div>
        </a>

        <ul class="menu-sub ">
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_products'))
                <li class="menu-item @if ($last_uri == 'products') active @endif">
                    <a href="{{ route('products.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Product List</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_generated_product_stocks'))
                <li class="menu-item @if ($last_uri == 'generated_product_stocks') active @endif">
                    <a href="{{ route('generated_product_stocks.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Generate Product</div>
                    </a>
                </li>
            @endif


        </ul>
    </li>
    @if (auth()->user()->hasRole(['Admin']) ||
            auth()->user()->can('list_settings'))
        <li class="menu-item @if ($last_uri == 'settings') active @endif">
            <a href="{{ route('settings.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Calendar">Settings</div>
            </a>
        </li>
    @endif
    <li class="menu-item @if (in_array($last_uri, $leads_arr)) open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cuboid"></i>
            <div data-i18n="Authentications">Manage Leads</div>
        </a>

        <ul class="menu-sub ">
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_lead_sources'))
                <li class="menu-item @if ($last_uri == 'lead_sources') active @endif">
                    <a href="{{ route('lead_sources.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Lead Sources</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_leads'))
                <li class="menu-item @if ($last_uri == 'leads') active @endif">
                    <a href="{{ route('leads.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Leads</div>
                    </a>
                </li>
            @endif



        </ul>
    </li>
    <li class="menu-item @if (in_array($last_uri, $spend_arr)) open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cuboid"></i>
            <div data-i18n="Authentications">Manage Expenditure</div>
        </a>

        <ul class="menu-sub ">
            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_spendable_items'))
                <li class="menu-item @if ($last_uri == 'spendable_items') active @endif">
                    <a href="{{ route('spendable_items.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Spendable Items</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole(['Admin']) ||
                    auth()->user()->can('list_expenses'))
                <li class="menu-item @if ($last_uri == 'expenses') active @endif">
                    <a href="{{ route('expenses.index') }}" class="menu-link">

                        <div data-i18n="Calendar">Manage Expenses</div>
                    </a>
                </li>
            @endif




        </ul>
    </li>


    <!-- User interface -->

</ul>
