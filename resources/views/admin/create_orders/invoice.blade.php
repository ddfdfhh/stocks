<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Site Title -->
    <title>General Purpose Invoice</title>
    <link rel="stylesheet" href="{{ asset('pdf.css') }}" />
</head>

<body>
    <div class="tm_container">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1" id="tm_download_section">
                <div class="tm_invoice_in">
                    <div class="tm_invoice_head tm_align_center tm_mb20">
                        <div class="tm_invoice_left">
                            <div class="tm_logo"><img src="{{ 'storage/settings/' . $settings->image }}" alt="Logo"></div>
                        </div>
                        <div class="tm_invoice_right tm_text_right">
                            <div class="tm_primary_color tm_f50 tm_text_uppercase">Invoice</div>
                        </div>
                    </div>
                    <div class="tm_invoice_info tm_mb20">
                        <div class="tm_invoice_seperator tm_gray_bg" style="background:white"></div>
                        <div class="tm_invoice_info_list">
                            <p class="tm_invoice_number tm_m0">Invoice No: <b
                                    class="tm_primary_color">#RBK{{ $row->id }}</b></p>

                            <p class="tm_invoice_date tm_m0">Date: <b class="tm_primary_color">{{ date('Y.m.d') }}</b>
                            </p>
                        </div>
                    </div>
                    <div class="tm_invoice_head tm_mb10">
                        <div class="tm_invoice_left" style="float: left; width: 25%;">
                            <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
                            <p>
                                {{ ucwords($customer->name) }} <br>
                                {{ ucwords($customer->address) }}<br>
                                {{ ucwords($customer->city->name) }},{{ ucwords($customer->state->name) }}<br>
                                +91 {{ $customer->mobile_no }}<br>
                                {{ $customer->email }}


                            </p>
                        </div>
                        <div class="tm_invoice_right tm_text_right" style="float: right; width: 65%;">
                            <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
                            <p>
                                {{ ucwords($settings->company_name) }} <br>
                                {{ ucwords($settings->address) }}<br>
                                +91{{ $settings->mobile_number }}<br>

                                GSTIN-<b>{{ $settings->gst_number }}</b>
                            </p>
                        </div>
                    </div>
                    <div class="tm_table tm_style1 tm_mb30">
                        <div class="tm_round_border">

                            @php
                                $sub_total = 0;
                                $total = 0;
                                $total_sgst_tax = 0;
                                $total_cgst_tax = 0;
                                $discount = 0;
                            @endphp
                         
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Product</th>

                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Qty</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Tax
                                                Inclusive?</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Tax</th>
                                            <th
                                                class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">
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
                                                <td
                                                    class="tm_width_1">{{ $item['name'] }}</td>
                                                <td class="tm_width_1">
                                                    &#8377;{{ number_format($item['price'], 2) }}</td>

                                                <td class="tm_width_1">{{ $item['quantity'] }}</td>
                                                <td class="tm_width_1">{{ $item['tax_inclusive'] }}</td>
                                                <td class="tm_width_3">
                                                     &#8377;{{ $sub_sgst }} sgst(@ {{ $item['sgst'] }}% )
                                                    &#8377;{{ $sub_cgst }}  csgst(@ {{ $item['cgst'] }}%) 
                                                </td>
                                                 <td class="tm_width_3" style="text-align:right">
                                                    &#8377;{{$sub_total}}
                                                </td>
                              
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tm_invoice_footer">
                            <div class="tm_left_footer" style="width:60%">
                                &nbsp;
                            </div>
                            <div class="tm_right_footer" style="float:right;width: 40%;">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtoal</td>
                                            <td
                                                class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                                                &#8377;{{ $sub_total }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Total SGST
                                            </td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                &#8377;{{ $total_sgst_tax }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Total CGST
                                            </td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                &#8377;{{ $total_cgst_tax }} </td>
                                        </tr>
                                        <tr class="tm_border_top tm_border_bottom">
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand
                                                Total </td>
                                            <td
                                                class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">
                                                &#8377;{{ $sub_total + $total_cgst_tax + $total_sgst_tax }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tm_padd_15_20 tm_round_border">
                        <p class="tm_mb5"><b class="tm_primary_color">Terms & Conditions:</b></p>
                        <ul class="tm_m0 tm_note_list">
                            <li>All claims relating to quantity or shipping errors shall be waived by Buyer unless made
                                in writing to Seller within thirty (30) days after delivery of goods to the address
                                stated.</li>

                        </ul>
                    </div><!-- .tm_note -->
                </div>
            </div>

        </div>
    </div>

</body>

</html>
