<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-primary" id="layout-navbar"
    style="
    color: white!important;
    box-shadow: -2px 8px 8px 0px silver;">
    <div class="container-fluid">








        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>


        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


            <!-- Search -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper mb-0" style="margin-top:8px">
                   
                    @if(auth()->user()->hasRole(['Store Incharge']))
                     <h5 class="text-white">Store Name-{{ ucwords(session()->get('store_name')) }}   </h5>
                     @else
                       <h5 class="text-white">Welcome,{{ ucwords(auth()->user()->name) }}   </h5>
                     @endif
                    
                </div>
            </div>
            <!-- /Search -->


            <ul class="navbar-nav flex-row align-items-center ms-auto">







               

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="pages-account-settings-account.html">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block lh-1">{{ucwords(auth()->user()->name)}}</span>
                                        <small>{{count(auth()->user()->getRoleNames())>0?auth()->user()->getRoleNames()[0]:''}}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                       @if(is_admin())
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="bx bx-cog me-2"></i>
                                <span class="align-middle">Settings</span>
                            </a>
                        </li>
                          @endif
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                </li>
            </ul>
            </li>
            <!--/ User -->


            </ul>
        </div>


        <!-- Search Small Screens -->
        <div class="navbar-search-wrapper search-input-wrapper  d-none">
            <input type="text" class="form-control search-input container-fluid border-0" placeholder="Search..."
                aria-label="Search...">
            <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
        </div>


    </div>
</nav>
