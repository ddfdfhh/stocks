@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-sm-6 col-xl-3 ">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Session</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">21,459</h4>
                                    <small class="text-success">(+29%)</small>
                                </div>
                                <small>Total Users</small>
                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="bx bx-user bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Paid Users</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">4,567</h4>
                                    <small class="text-success">(+18%)</small>
                                </div>
                                <small>Last week analytics </small>
                            </div>
                            <span class="badge bg-label-danger rounded p-2">
                                <i class="bx bx-user-plus bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Active Users</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">19,860</h4>
                                    <small class="text-danger">(-14%)</small>
                                </div>
                                <small>Last week analytics</small>
                            </div>
                            <span class="badge bg-label-success rounded p-2">
                                <i class="bx bx-group bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Pending Users</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">237</h4>
                                    <small class="text-success">(+42%)</small>
                                </div>
                                <small>Last week analytics</small>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                                <i class="bx bx-user-voice bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-7 col-lg-7 mb-4 mt-4 mb-md-0">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center me-3">
                            <img src="{{ asset('assets/img/avatars/4.png') }}" alt="Avatar" class="rounded-circle me-3"
                                width="54">
                            <div class="card-title mb-0">
                                <h5 class="mb-0">Financial Report for Kiara Cruiser</h5>
                                <small class="text-muted">Awesome App for Project Management</small>
                            </div>
                        </div>
                        <div class="dropdown btn-pinned">
                            <button class="btn p-0" type="button" id="financoalReport" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="financoalReport">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-4 mb-5 mt-4">
                            <div class="d-flex flex-column me-2">
                                <h6>Start Date</h6>
                                <span class="badge bg-label-success">02 APR 22</span>
                            </div>
                            <div class="d-flex flex-column me-2">
                                <h6>End Date</h6>
                                <span class="badge bg-label-danger">06 MAY 22</span>
                            </div>
                            <div class="d-flex flex-column me-2">
                                <h6>Members</h6>
                                <ul class="list-unstyled me-2 d-flex align-items-center avatar-group mb-0">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Vinnie Mostowy" class="avatar avatar-xs pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Allen Rieske" class="avatar avatar-xs pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Julee Rossignol" class="avatar avatar-xs pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Ellen Wagner" class="avatar avatar-xs pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/14.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Darcey Nooner" class="avatar avatar-xs pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/10.png" alt="Avatar">
                                    </li>
                                </ul>
                            </div>
                            <div class="d-flex flex-column me-2">
                                <h6>Budget</h6>
                                <span>$249k</span>
                            </div>
                            <div class="d-flex flex-column me-2">
                                <h6>Expenses</h6>
                                <span>$82k</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-nowrap d-block mb-1">Kiara Cruiser Progress</span>
                            <div class="progress w-100 mb-3" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <span>I distinguish three main text objectives. First, your objective could be merely to inform
                            people. A second be to persuade people.</span>
                    </div>
                    <div class="card-footer border-top">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><i class="bx bx-check"></i> 74 Tasks</li>
                            <li class="list-inline-item"><i class="bx bx-chat"></i> 678 Comments</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Finance Summary -->

            <!-- Activity Timeline -->
            <div class="col-md-5 mt-4 col-lg-5 mb-0">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Activity Timeline</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="timelineWapper" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="timelineWapper">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Activity Timeline -->
                        <ul class="timeline">
                            <li class="timeline-item timeline-item-transparent ps-4">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event pb-2">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">12 Invoices have been paid</h6>
                                        <small class="text-muted">12 min ago</small>
                                    </div>
                                    <p class="mb-2">Invoices have been paid to the company</p>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="me-3">
                                            <img src="../../assets/img/icons/misc/pdf.png" alt="PDF image" width="23"
                                                class="me-2">
                                            <span class="fw-bold text-body">Invoices.pdf</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent ps-4">
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event pb-2">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Client Meeting</h6>
                                        <small class="text-muted">45 min ago</small>
                                    </div>
                                    <p class="mb-2">Project meeting with john @10:15am</p>
                                    <div class="d-flex flex-wrap">
                                        <div class="avatar me-3">
                                            <img src="../../assets/img/avatars/1.png" alt="Avatar"
                                                class="rounded-circle" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0">John Doe (Client)</h6>
                                            <span class="text-muted">CEO of Pixinvent</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent ps-4">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event pb-0">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Create a new project for client</h6>
                                        <small class="text-muted">2 Day Ago</small>
                                    </div>
                                    <p class="mb-2">5 team members in a project</p>
                                    <div class="d-flex align-items-center avatar-group">
                                        <div class="avatar avatar-sm pull-up" data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy">
                                            <img src="../../assets/img/avatars/5.png" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                        <div class="avatar avatar-sm pull-up" data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom" data-bs-placement="top" title="Marrie Patty">
                                            <img src="../../assets/img/avatars/12.png" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                        <div class="avatar avatar-sm pull-up" data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom" data-bs-placement="top" title="Jimmy Jackson">
                                            <img src="../../assets/img/avatars/9.png" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                        <div class="avatar avatar-sm pull-up" data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom" data-bs-placement="top" title="Kristine Gill">
                                            <img src="../../assets/img/avatars/6.png" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                        <div class="avatar avatar-sm pull-up" data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom" data-bs-placement="top" title="Nelson Wilson">
                                            <img src="../../assets/img/avatars/14.png" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-end-indicator">
                                <i class="bx bx-check-circle"></i>
                            </li>
                        </ul>
                        <!-- /Activity Timeline -->
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
