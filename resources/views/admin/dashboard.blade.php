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
                                <span>Customers</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">2</h4>
                                  
                                </div>
                                <small>Total Active</small>
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
                                <span>Invoice Generated</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">4,567</h4>
                                    <small class="text-success">(+18%)</small>
                                </div>
                                <small>Total Sell </small>
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
                                <span>Total Sell</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">&#8377; 19,860</h4>
                                    
                                </div>
                                <small>Amount</small>
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
                                <span> Expenditure </span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">&#8377; 23,700</h4>
                                   
                                </div>
                                <small>Total</small>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                                <i class="bx bx-user-voice bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>


         
           

        </div>
    </div>
@endsection
