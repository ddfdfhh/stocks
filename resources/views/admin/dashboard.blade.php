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

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i
                                            class='bx bx-user fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ $total_customers }}</h5>
                                    <small class="text-muted">Customers</small>
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
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($income) }} &#8377;</h5>
                                    <small class="text-muted">Total Profit</small>
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
                                    <span class="avatar-initial rounded-circle bg-label-success"><i
                                            class='bx bx-wallet fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($total_order_income) }} &#8377;</h5>
                                    <small class="text-muted">Total Order Income</small>
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
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i
                                            class='fa fa-inr fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($today_order_income) }} &#8377;</h5>
                                    <small class="text-muted">Today Order Income</small>
                                </div>
                            </div>
                            <div id="expensesLineChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($total_sell) }}</h5>
                                    <small class="text-muted">Total Income</small>
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
                                            class='fa fa-inr  fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($monthly_sell) }}</h5>
                                    <small class="text-muted">Monthly Income</small>
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
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($weekly_sell) }}</h5>
                                    <small class="text-muted">Weekly Income</small>
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
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($today_sell) }}</h5>
                                    <small class="text-muted">Today Income </small>
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
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i
                                            class='bx bx-cart fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($total_expense) }} &#8377;</h5>
                                    <small class="text-muted">Total Expense</small>
                                </div>
                            </div>
                            <div id="expensesLineChart"></div>
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
                                            class='fa fa-inr  fs-4'></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($monthly_expense) }}</h5>
                                    <small class="text-muted">Monthly Expense</small>
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
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($weekly_expense) }}</h5>
                                    <small class="text-muted">Weekly Expense</small>
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
                                    <h5 class="card-title mb-0 me-2">{{ formateNumber($today_expense) }}</h5>
                                    <small class="text-muted">Today Expense </small>
                                </div>
                            </div>
                            <div id="profitChart"></div>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <h5 class="card-title mb-0 me-2">{{$total_leads_entered }}</h5>
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
                                    <h5 class="card-title mb-0 me-2">{{$today_leads }}</h5>
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
                                    <h5 class="card-title mb-0 me-2">{{ $today_leads_success  }}</h5>
                                    <small class="text-muted">Today Converted Leads </small>
                                </div>
                            </div>
                            <div id="profitChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row mt-3">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daily Sell vs Expenditure</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>




        </div>

        <div class="accordion mt-3 accordion-header-primary" id="accordionWithIcon">
            <div class="card accordion-item active">
                <h2 class="accordion-header d-flex align-items-center">
                    <button type="button" class="accordion-button" data-bs-toggle="collapse"
                        data-bs-target="#accordionWithIcon-1" aria-expanded="true">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i>
                        Order Report
                    </button>
                </h2>

                <div id="accordionWithIcon-1" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="order_daily_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="order_weekly_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="order_monthly_chart"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="paid_order_daily_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="paid_order_weekly_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="paid_order_monthly_chart"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row gx-2 row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-6 col-12">

                                <div class="card ">
                                    <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                                        <h5 class="card-title mb-0 text-white ">Latest Orders <i
                                                class="fa fa-long-arrow-right"></i> </h5>
                                        <a href="{{ route('create_orders.index') }}" class="btn btn-xs btn-warning"
                                            style="margin-left:5px">View All</a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-borderless mb-1">
                                            <thead class="border-bottom">
                                                <tr>
                                                    <th class="pt-0">Title</th>
                                                    <th class="pt-0">Total Amount</th>
                                                    <th class="pt-0">Status</th>

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
                                                            <b> &#8377; {{ $r->total }}</b>
                                                        </td>
                                                        <td>
                                                           
                                                             <x-paymentStatus :status="$r->paid_status" />
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">

                                <div class="card ">
                                    <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                                        <h5 class="card-title mb-0 text-white ">Latest Paid Orders <i
                                                class="fa fa-long-arrow-right"></i> </h5>
                                        <a href="{{ route('create_orders.index') }}" class="btn btn-xs btn-warning"
                                            style="margin-left:5px">View All</a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-borderless mb-1">
                                            <thead class="border-bottom">
                                                <tr>
                                                    <th class="pt-0">Title</th>
                                                    <th class="pt-0">Total Amount</th>

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
                                                            <b> &#8377; {{ $r->total }}</b>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item card">
                <h2 class="accordion-header d-flex align-items-center">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionWithIcon-2" aria-expanded="false">
                        <i class="bx bx-briefcase me-2"></i>
                        Leads Report
                    </button>
                </h2>
                <div id="accordionWithIcon-2" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="row row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="leads_daily_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="leads_weekly_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="leads_monthly_chart"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="suc_leads_daily_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="suc_leads_weekly_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="suc_leads_monthly_chart"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row gx-2 row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-6 col-12">

                                <div class="card ">
                                    <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                                        <h5 class="card-title mb-0 text-white ">Latest Leads <i
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
                            <div class="col-md-6 col-12">

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
                    </div>
                </div>
            </div>

            <div class="accordion-item card">
                <h2 class="accordion-header d-flex align-items-center">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#accordionWithIcon-3" aria-expanded="false">
                        <i class="bx bx-gift me-2"></i>
                        Sell Report
                    </button>
                </h2>
                <div id="accordionWithIcon-3" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="row row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="sell_daily_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="sell_weekly_chart"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-0">

                                <div class="card-body p-0">
                                    <div id="sell_monthly_chart"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row gx-2 row-bordered m-0">
                            <!-- Order Summary -->
                            <div class="col-md-12 col-12">

                                <div class="card ">
                                    <div class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                                        <h5 class="card-title mb-0 text-white ">Latest Sales <i
                                                class="fa fa-long-arrow-right"></i> </h5>
                                        <a href="{{ route('create_orders.index') }}" class="text-white btn btn-xs btn-warning"
                                            style="margin-left:5px">View All</a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-borderless mb-1">
                                            <thead class="border-bottom">
                                                <tr>
                                                    <th class="pt-0">Title</th>
                                                    <th class="pt-0">Amount</th>
                                                    <th class="pt-0">Date</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($top_five_sells as $r)
                                                    <tr>
                                                        <td
                                                            style="max-width:400px;
                                        word-wrap: break-word; ">
                                                            {{ ucwords($r->name) }}
                                                        </td>
                                                        <td>
                                                            &#8377;{{ $r->amount }}
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
                    </div>
                </div>
                <div class="accordion-item card">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                            data-bs-target="#accordionWithIcon-4" aria-expanded="false">
                            <i class="bx bx-gift me-2"></i>
                            Expenditure Report
                        </button>
                    </h2>
                    <div id="accordionWithIcon-4" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="row row-bordered m-0">
                                <!-- Order Summary -->
                                <div class="col-md-4 col-12 px-0">

                                    <div class="card-body p-0">
                                        <div id="exp_daily_chart"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 px-0">

                                    <div class="card-body p-0">
                                        <div id="exp_weekly_chart"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 px-0">

                                    <div class="card-body p-0">
                                        <div id="exp_monthly_chart"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row gx-2 row-bordered m-0">
                                <!-- Order Summary -->
                                <div class="col-md-12 col-12">

                                    <div class="card ">
                                        <div
                                            class="card-header bg-info text-white d-flex justify-content-between flex-wrap">
                                            <h5 class="card-title mb-0 text-white ">Latest Expenses <i
                                                    class="fa fa-long-arrow-right"></i> </h5>
                                            <a href="{{ route('expenses.index') }}" class="text-white btn btn-xs btn-warning"
                                                style="margin-left:5px">View All</a>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-borderless mb-1">
                                                <thead class="border-bottom">
                                                    <tr>
                                                        <th class="pt-0">Title</th>
                                                        <th class="pt-0">Amount</th>
                                                        <th class="pt-0">Date</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($top_five_expense as $r)
                                                        <tr>
                                                            <td
                                                                style="max-width:400px;
                                        word-wrap: break-word; ">
                                                                {{ ucwords($r->name) }}
                                                            </td>
                                                            <td>
                                                                &#8377; {{ $r->amount }}
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
                        </div>
                    </div>
                </div>



            </div>
        @endsection
