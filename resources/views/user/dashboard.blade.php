@extends('layouts.admin.app')
@section('content')
    <style>
        .card-header {
            padding: 11px 24px !important;

            padding-top: 16px !important;
        }

        h5.card-title {
            font-size: 14px !important;
        }

        td {
            font-size: 13px !important;
        }

        .bg-c-blue {
            background: linear-gradient(45deg, #4099ff, #73b4ff);
        }

        .bg-c-green {
            background: linear-gradient(45deg, #2ed8b6, #59e0c5);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg, #FFB64D, #ffcb80);
        }

        .bg-c-pink {
            background: linear-gradient(45deg, #FF5370, #ff869a);
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">

        @if (!auth()->user()->hasRole(['Store Incharge']))
            <div class="row">

                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-danger"><i
                                                class='bx bx-purchase-tag fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_leads_entered }}</h5>
                                        <small class="text-muted">Total Leads Generated</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-info"><i
                                                class='bx bx-dock-top  fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $today_leads }}</h5>
                                        <small class="text-muted">Today Leads Generated</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-warning"><i
                                                class='fa fa-inr fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_leads_success }}</h5>
                                        <small class="text-muted">Total Converted Leads </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                class='fa fa-inr  fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $today_leads_success }}</h5>
                                        <small class="text-muted">Today Converted Leads </small>
                                    </div>
                                </div>
                                <div id="profitChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-warning"><i
                                                class='fa fa-inr fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_leads_failed }}</h5>
                                        <small class="text-muted">Total Failed Leads </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                class='fa fa-inr  fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_leads_active }}</h5>
                                        <small class="text-muted">Todal Active Leads </small>
                                    </div>
                                </div>
                                <div id="profitChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row gx-2 row-bordered m-0">
                <!-- Order Summary -->
                <div class="col-md-6 col-6">

                    <div class="card ">
                        <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                            <h5 class="card-title mb-0 text-white ">Today Followup Leads <i
                                    class="fa fa-long-arrow-right"></i>
                            </h5>
                            <a href="{{ route('leads.followup_leads') }}" class="text-white btn btn-xs btn-warning"
                                style="margin-left:5px">View All</a>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless mb-1">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pt-0">Title</th>
                                        <th class="pt-0">Status</th>
                                        <th class="pt-0">Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_five_today_followup as $r)
                                        <tr>
                                            <td
                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                {{ ucwords($r->title) }}
                                            </td>
                                            <td>
                                                <x-leadStatus :status="$r->status" />
                                            </td>
                                            <td>
                                                {{ formateDate($r->created_at) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-6">

                    <div class="card ">
                        <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                            <h5 class="card-title mb-0 text-white ">Upcoming Followup Leads <i
                                    class="fa fa-long-arrow-right"></i> </h5>
                            <a href="{{ route('leads.followup_leads') }}" class="text-white btn btn-xs btn-warning"
                                style="margin-left:5px">View All</a>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless mb-1">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pt-0">Title</th>
                                        <th class="pt-0">Status</th>
                                        <th class="pt-0">Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_five_followup as $r)
                                        <tr>
                                            <td
                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                {{ ucwords($r->title) }}
                                            </td>
                                            <td>
                                                <x-leadStatus :status="$r->status" />
                                            </td>
                                            <td>
                                                {{ formateDate($r->created_at) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
            <br>
            <div class="row gx-2 row-bordered m-0">
                <!-- Order Summary -->
                <div class="col-md-6 col-6">

                    <div class="card ">
                        <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                            <h5 class="card-title mb-0 text-white ">Latest Leads <i class="fa fa-long-arrow-right"></i>
                            </h5>
                            <a href="{{ route('leads.index') }}" class="text-white btn btn-xs btn-warning"
                                style="margin-left:5px">View All</a>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless mb-1">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pt-0">Title</th>
                                        <th class="pt-0">Status</th>
                                        <th class="pt-0">Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_five_leads as $r)
                                        <tr>
                                            <td
                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                {{ ucwords($r->title) }}
                                            </td>
                                            <td>
                                                <x-leadStatus :status="$r->status" />
                                            </td>
                                            <td>
                                                {{ formateDate($r->created_at) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-6">

                    <div class="card ">
                        <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                            <h5 class="card-title mb-0 text-white ">Latest Converted Leads <i
                                    class="fa fa-long-arrow-right"></i> </h5>
                            <a href="{{ route('leads.index') }}" class="text-white btn btn-xs btn-warning"
                                style="margin-left:5px">View All</a>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless mb-1">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pt-0">Title</th>
                                        <th class="pt-0">Status</th>
                                        <th class="pt-0">Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_five_converted_leads as $r)
                                        <tr>
                                            <td
                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                {{ ucwords($r->title) }}
                                            </td>
                                            <td>
                                                <x-leadStatus :status="$r->status" />
                                            </td>
                                            <td>
                                                {{ formateDate($r->created_at) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        @else
            {{-- for store owner --}}
            <div class="row">
                <!--leads-->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-danger"><i
                                                class='bx bx-purchase-tag fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_orders_count }}</h5>
                                        <small class="text-muted">Total orders</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-info"><i
                                                class='bx bx-dock-top  fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $today_orders_count }}</h5>
                                        <small class="text-muted">Today Orders </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-warning"><i
                                                class='fa fa-inr fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_orders_paid_count }}</h5>
                                        <small class="text-muted">TotalPaid Order </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                class='fa fa-inr  fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $today_orders_paid_count }}</h5>
                                        <small class="text-muted">Today Paid Order </small>
                                    </div>
                                </div>
                                <div id="profitChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-info"><i
                                                class='bx bx-dock-top  fs-4'></i></span>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2">{{ $total_products_count }}</h5>
                                        <small class="text-muted">No Of Store Products </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="row gx-2 row-bordered m-0">
                <!-- Order Summary -->
                <div class="col-md-6 col-6">

                    <div class="card ">
                        <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                            <h5 class="card-title mb-0 text-white ">Latest Order <i class="fa fa-long-arrow-right"></i>
                            </h5>
                            <a href="{{ route('create_orders.index') }}" class="text-white btn btn-xs btn-warning"
                                style="margin-left:5px">View All</a>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless mb-1">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pt-0">Title</th>
                                        <th class="pt-0">Total</th>
                                        <th class="pt-0">Paid</th>
                                        <th class="pt-0">Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_five_orders as $r)
                                        <tr>
                                            <td
                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                {{ ucwords($r->title) }}
                                            </td>
                                            <td>
                                                {{ $r->total }}
                                            </td>
                                            <td>
                                                {{ $r->paid_amount }}
                                            </td>
                                            <td>
                                                {{ formateDate($r->created_at) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-6">

                    <div class="card ">
                        <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                            <h5 class="card-title mb-0 text-white ">Latest Paid Order <i
                                    class="fa fa-long-arrow-right"></i>
                            </h5>
                            <a href="{{ route('create_orders.index') }}" class="text-white btn btn-xs btn-warning"
                                style="margin-left:5px">View All</a>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless mb-1">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="pt-0">Title</th>
                                        <th class="pt-0">Total</th>
                                        <th class="pt-0">Paid</th>
                                        <th class="pt-0">Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_five_orders_paid as $r)
                                        <tr>
                                            <td
                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                {{ ucwords($r->title) }}
                                            </td>
                                            <td>
                                                {{ $r->total }}
                                            </td>
                                            <td>
                                                {{ $r->paid_amount }}
                                            </td>
                                            <td>
                                                {{ formateDate($r->created_at) }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        @endif
    </div>
@endsection
