@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Leads/</span> View
        </h4>
        <div class="row gy-4">
            <!-- User Sidebar -->
            <div class="col-xl-5 col-lg-65 col-md-6 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">


                        <h5 class="pb-2 border-bottom mb-4">Leads Details</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Title:</span>
                                    <span>{{ ucwords($row->title) }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Lead Name:</span>
                                    <span>{{ ucwords($row->lead_name) }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Email:</span>
                                    <span>{{ $row->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Phone No:</span>
                                    <span>(+91) {{ $row->lead_phone_no }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Whatsapp No:</span>
                                    <span>(+91) {{ $row->whatsapp_no }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Company Name:</span>
                                    <span>{{ ucwords($row->company_name) }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Address:</span>
                                    <span>{{ ucwords($row->address) }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Designation:</span>
                                    <span>{{ ucwords($row->designation) }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Status:</span>
                                    <span class="badge bg-label-success">
                                        {{ ucwords($row->status) }}
                                    </span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Lead Type:</span>
                                    <span class="badge bg-label-success">
                                        {{ ucwords($row->type) }}
                                    </span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Source:</span>
                                    <span>{{ ucwords($row->source->name) }}</span>
                                </li>

                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-3">
                                @php
                                    $editurl = route($plural_lowercase . '.edit', [\Str::singular($plural_lowercase) => $row->id]);
                                @endphp

                                <a href="{{ $editurl }}" class="rounded-0 btn btn-primary me-3"><i
                                        class="fa fa-edit"></i> Edit</a>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->
                <div class="card">
                    <div class="card-body">

                        <h5 class="pb-2 border-bottom mb-4">Enquired Products Details</h5>
                        {!! showArrayInColumnNoPopup(json_decode($row->enquired_products_detail, true), 'product_id') !!}
                    </div>
                </div>
                <!-- /Plan Card -->
            </div>
            <!--/ User Sidebar -->


            <!-- User Content -->
            <div class="col-xl-7 col-lg-6 col-md-6 order-0 order-md-1">

                <!--/ User Pills -->

                <!-- Change Password -->
                <div class="card">
                    <h5 class="card-header">Remarks</h5>
                    <div class="table-responsive">
                        <table class="table border-top">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Remarks</th>
                                    <th class="text-truncate">Date</th>
                                    <th class="text-truncate">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $conversations = $row->conversations ? json_decode($row->conversations, true) : [];
                                @endphp
                                @if ($row->conversations && count($conversations) > 0)
                                    @foreach ($conversations as $item)
                                        <tr id="row-{{ $item['by_user_id'] }}">

                                            <td class="text-truncate" style="word-wrap: break-word;max-width:600px;">
                                                {{ $item['message'] }}</td>
                                            <td class="text-truncate">{{ formateDate($item['date'], true) }}</td>
                                            <td class="text-truncate">
                                                <button class="btn btn-xs btn-danger"
                                                    onClick="deleteJsonColumnData({!! $row->id !!},'id','leads','{!! $item['id'] !!}','conversations','{!! route('deleteInJsonColumnData') !!}')">
                                                    <i class="bx bx-trash"></i></button>
                                            </td>

                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align:center">No Data Available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <h5 class="card-header">Add Remark</h5>
                    <div class="card-body">
                        <form data-module="Remark" id="remark_form-{{ $row->id }}"
                            data-url="{{ route('addEditRemark') }}">

                            <div class="row">
                                <div class="mb-3 col-12 col-sm-12 ">
                                    <div id="resp-{{ $row->id }}"></div>
                                    <input type="hidden" name="lead_id" id="lead_id-{{ $row->id }}"
                                        value="{{ $row->id }}" />
                                    <textarea type="text" required name="conversation" id="conversation-{{ $row->id }}"
                                        class="form-control p-4 mt-3" placeholder="Add Conversation" aria-describedby="subscribe"></textarea>
                                </div>


                                <div>
                                    <button type="button" onclick="addEditRemark('{!! $row->id !!}')"
                                        id="remark_btn-{{ $row->id }}" class="rounded-0 btn btn-primary me-2"><i
                                            class="fa fa-plus-circle"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->


                <!--/ Two-steps verification -->

                <!-- Recent Devices -->

                <!--/ Recent Devices -->
            </div>
            <!--/ User Content -->
        </div>



    </div>
@endsection
