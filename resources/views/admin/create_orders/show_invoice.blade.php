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


    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice-print.css') }}" />
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
            <div class="col-sm-6 w-8">
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ ucwords($customer->name) }}</p>
                <p class="mb-1"><span class="me-1 fw-bold">Address-</span> {{ ucwords($customer->address) }}</p>
                <p class="mb-1">{{ ucwords($customer->city->name) }},{{ ucwords($customer->state->name) }},INDIA
                </p>

                <p class="mb-1"><span class="me-1 fw-bold">Phone No-</span> +91 {{ $customer->mobile_no }}</p>
                <p class="mb-0"><span class="me-1 fw-bold">Email-</span>{{ $customer->email }}</p>
                <p class="mb-0"><span class="me-1 fw-bold">GSTIN-</span>{{ $customer->gst_number }}</p>
            </div>
            <div class="col-sm-6 w-2" style="text-align:right;">
                <h6>Bill To:</h6>
                <p class="mb-1">{{ ucwords($customer->name) }}</p>
                <p class="mb-1"> {{ ucwords($customer->address) }}</p>
                <p class="mb-1">{{ ucwords($customer->city->name) }},{{ ucwords($customer->state->name) }},INDIA
                </p>

                <p class="mb-1"> +91 {{ $customer->mobile_no }}</p>
                <p class="mb-0">{{ $customer->email }}</p>
                <p class="mb-0">{{ $customer->gst_number }}</p>
            </div>
        </div>
        @php
            $sub_total = 0;
            $total = 0;
            $total_sgst_tax = 0;
            $total_cgst_tax = 0;
            $discount = 0;
        @endphp
        <div class="table-responsive">
            <table class="table border-top m-0">
                <thead>
                    <tr>
                        <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Product</th>

                        <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price</th>
                        <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Qty</th>
                        <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Tax
                            Inclusive?</th>
                        <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Tax</th>
                        <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">
                            Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (json_decode($row->items, true) as $item)
                        @php
                            
                            $item['cgst'] = isset($item['cgst']) ? $item['cgst'] : 18;
                            $item['sgst'] = isset($item['sgst']) ? $item['sgst'] : 18;
                            $item['tax_inclusive'] = isset($item['tax_inclusive']) ? $item['tax_inclusive'] : 'Yes';
                            $sub_sgst = $item['tax_inclusive'] == 'Yes' ? ($item['price'] - 1) * ($item['sgst'] / 100) : ($item['price'] * $item['sgst']) / 100;
                            $sub_cgst = $item['tax_inclusive'] == 'Yes' ? ($item['price'] - 1) * ($item['cgst'] / 100) : ($item['price'] * $item['cgst']) / 100;
                            $sub = $item['price'] * $item['quantity'];
                            $sub_total += $sub;
                            
                            $total_sgst_tax += $sub_sgst * $item['quantity'];
                            $total_cgst_tax += $sub_cgst * $item['quantity'];
                        @endphp
                        <tr>
                            <td class="tm_width_1">{{ $item['name'] }}</td>
                            <td class="tm_width_1">
                                &#8377;{{ number_format($item['price'], 2) }}</td>

                            <td class="tm_width_1">{{ $item['quantity'] }}</td>
                            <td class="tm_width_1">{{ $item['tax_inclusive'] }}</td>
                            <td class="tm_width_3">
                                &#8377;{{ $sub_sgst }} sgst(@ {{ $item['sgst'] }}% )<br>
                                &#8377;{{ $sub_cgst }} csgst(@ {{ $item['cgst'] }}%)
                            </td>
                            <td class="tm_width_3" style="text-align:right">
                                &#8377;{{ $sub_total }}
                            </td>

                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="align-top px-4 py-3">

                        </td>
                        <td class="text-end px-4 py-3">
                            <p class="mb-2">Subtotal:</p>

                            <p class="mb-2">Total SGST Tax:</p>
                            <p class="mb-2">Total CGST Tax:</p>
                            <p class="mb-0">Total:</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="fw-semibold mb-2"> &#8377;{{ $sub_total }}</p>
                            <p class="fw-semibold mb-2"> &#8377;{{ $total_sgst_tax }}</p>
                            <p class="fw-semibold mb-2"> &#8377;{{ $total_cgst_tax }}</p>
                            <p class="fw-semibold mb-0">
                                &#8377;{{ $sub_total + $total_cgst_tax + $total_sgst_tax }}
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
</body>

</html>
