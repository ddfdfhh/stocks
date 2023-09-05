<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed " dir="ltr" data-theme="theme-semi-dark"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-semi-dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard -</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 5" />
    <meta name="keywords"
        content="dashboard, bootstrap 5 dashboard, bootstrap 5 admin, bootstrap 5 design, bootstrap 5">
    <!-- Canonical SEO -->
    <!-- Favicon -->
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-semi-dark.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice-print.css')}}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  @yield('css')
</head>
<style>
    .btn-icon {
        width: 30px !important;
        height: 30px !important;
    }

    .btn-icon i {
        font-size: 17px !important;
    }

    .fieldset {
        border: 1px solid #e0e0e0 !important;
        padding: 10px;
        height: vh;
        margin-bottom: 5px;
    }

    .legend {
        border-style: none;
        border-width: 0;
        float: none !important;
        font-size: 14px;
        line-height: 20px;
        margin-bottom: 0;
        width: auto;
        padding: 0 10px;
        color: #5782c7;
        font-weight: 500;
    }

    .form-label,
    .col-form-label {

        text-transform: none !important;
</style>
<style>
    #template-customizer {
        display: none
    }

    .image_preview_box {
        display: inline-block;
        max-width: 122px;
        margin: 2px;
        border: 1px solid silver;
        padding: 2px;
        position: relative;
    }

    .image_preview_box i.remove {
        color: red;
        top: 0;
        right: 5px;
        position: absolute;
        cursor: pointer;
    }
</style>

<body>
    <div class="invoice-print p-5">

        <div class="d-flex justify-content-between flex-row ">
            <div class="mb-4">
                <div class="d-flex svg-illustration mb-3 gap-2 ">

                    <img src="{{ asset('storage/settings/' . $settings->image) }}" style="width:50px;height:50px;" />


                    <span class="app-brand-text h3 mb-0 fw-bold">{{ ucwords($settings->company_name) }}</span>
                </div>
                <p class="mb-1"><span class="me-1 fw-bold">Address:</span> {{ ucwords($settings->address) }}</p>

                <p class="mb-0"><span class="me-1 fw-bold">Phone No:</span> +91{{ $settings->mobile_number }}</p>
                <p class="mb-0"><span class="me-1 fw-bold">GSTIN:</span>{{ $settings->gst_number }}</p>
            </div>
            <div>
                <h4>Invoice #{{ $row->id }}</h4>
                <div class="mb-2">
                    <span>Date Issues:</span>
                    <span class="fw-semibold">{{ formateDate(date('Y-m-d')) }}</span>
                </div>
                <div>
                    <span>Date Due:</span>
                    <span class="fw-semibold">{{ formateDate(date('Y-m-d')) }}</span>
                </div>
            </div>
        </div>

        <hr />

        <div class="row d-flex justify-content-between mb-4">
            <div class="col-sm-6 w-50">
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ ucwords($customer->name) }}</p>
                <p class="mb-1"><span class="me-1 fw-bold">Address-</span> {{ ucwords($customer->address) }}</p>
                <p class="mb-1">{{ ucwords($customer->city->name) }},{{ ucwords($customer->state->name) }},INDIA
                </p>

                <p class="mb-1"><span class="me-1 fw-bold">Phone No-</span> +91 {{ $customer->mobile_no }}</p>
                <p class="mb-0"><span class="me-1 fw-bold">Email-</span>{{ $customer->email }}</p>
                <p class="mb-0"><span class="me-1 fw-bold">GSTIN-</span>{{ $customer->gst_number }}</p>
            </div>
            <div class="col-sm-6 w-50 " style="text-align:right">
                <h6>Bill To:</h6>
                <p class="mb-1">Ram Ashish</p>
                <p class="mb-1">ByPass Nearlby,Sulatnapur</p>

                <p class="mb-1">718-986-6062</p>
                <p class="mb-0">ramashishs@gmail.com</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table border-top m-0">
                <thead>
                    <tr style="background-color:black">
                        <th style="color:white">Item</th>

                        <th style="color:white">Code</th>
                        <th style="color:white">Qty</th>
                        <th style="color:white">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $tax = 0;
                        $discount = 0;
                    @endphp
                    @foreach (json_decode($row->items, true) as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>PLI90</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>&#8377;{{ number_format($item['price'], 2) }}</td>

                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="align-top px-4 py-3">

                        </td>
                        <td class="text-end px-4 py-3">
                            <p class="mb-2">Subtotal:</p>
                            <p class="mb-2">Discount:</p>
                            <p class="mb-2">Tax:</p>
                            <p class="mb-0">Total:</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="fw-semibold mb-2"> &#8377;{{ number_format($row->total, 2) }}</p>
                            <p class="fw-semibold mb-2">&#8377;0.00</p>
                            <p class="fw-semibold mb-2">&#8377;0.00</p>
                            <p class="fw-semibold mb-0"> &#8377;{{ number_format($row->total, 2) }}
                        </td>
                        </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-12">
                <span class="fw-semibold">Note:</span>
                <span>Some Note. Thank You!</span>
            </div>
        </div>

    </div>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/app-invoice-print.js') }}"></script>
</body>
</html>
