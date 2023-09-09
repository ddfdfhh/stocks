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
                            <div class="tm_logo"><img src="assets/img/logo.svg" alt="Logo"></div>
                        </div>
                        <div class="tm_invoice_right tm_text_right">
                            <div class="tm_primary_color tm_f50 tm_text_uppercase">Invoice</div>
                        </div>
                    </div>
                    <div class="tm_invoice_info tm_mb20">
                        <div class="tm_invoice_seperator tm_gray_bg"></div>
                        <div class="tm_invoice_info_list">
                            <p class="tm_invoice_number tm_m0">Invoice No: <b class="tm_primary_color">#LL93784</b></p>
                            <p class="tm_invoice_date tm_m0">Date: <b class="tm_primary_color">01.07.2022</b></p>
                        </div>
                    </div>
                    <div class="tm_invoice_head tm_mb10">
                        <div class="tm_invoice_left" style="float: left; width: 25%;">
                            <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
                            <p>
                                Lowell H. Dominguez <br>
                                84 Spilman Street, London <br>United Kingdom <br>
                                lowell@gmail.com
                            </p>
                        </div>
                        <div class="tm_invoice_right tm_text_right" style="float: right; width: 65%;">
                            <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
                            <p>
                                Laralink Ltd <br>
                                86-90 Paul Street, London<br>
                                England EC2A 4NE <br>
                                demo@gmail.com
                            </p>
                        </div>
                    </div>
                    <div class="tm_table tm_style1 tm_mb30">
                        <div class="tm_round_border">
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Item</th>
                                            <th class="tm_width_4 tm_semi_bold tm_primary_color tm_gray_bg">Description
                                            </th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Qty</th>
                                            <th
                                                class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3">1. Website Design</td>
                                            <td class="tm_width_4">Six web page designs and three times revision</td>
                                            <td class="tm_width_2">$350</td>
                                            <td class="tm_width_1">1</td>
                                            <td class="tm_width_2 tm_text_right">$350</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3">2. Web Development</td>
                                            <td class="tm_width_4">Convert pixel-perfect frontend and make it dynamic
                                            </td>
                                            <td class="tm_width_2">$600</td>
                                            <td class="tm_width_1">1</td>
                                            <td class="tm_width_2 tm_text_right">$600</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3">3. App Development</td>
                                            <td class="tm_width_4">Android & Ios Application Development</td>
                                            <td class="tm_width_2">$200</td>
                                            <td class="tm_width_1">2</td>
                                            <td class="tm_width_2 tm_text_right">$400</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3">4. Digital Marketing</td>
                                            <td class="tm_width_4">Facebook, Youtube and Google Marketing</td>
                                            <td class="tm_width_2">$100</td>
                                            <td class="tm_width_1">3</td>
                                            <td class="tm_width_2 tm_text_right">$300</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tm_invoice_footer">
                            <div class="tm_left_footer" style="width:60%">
                             sbsd
                            </div>
                            <div class="tm_right_footer" style="float:right;width: 40%;">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtoal</td>
                                            <td
                                                class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                                                $1650</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Tax <span
                                                    class="tm_ternary_color">(5%)</span></td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                +$82</td>
                                        </tr>
                                        <tr class="tm_border_top tm_border_bottom">
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand
                                                Total </td>
                                            <td
                                                class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">
                                                $1732</td>
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
                            <li>Delivery dates are not guaranteed and Seller has no liability for damages that may be
                                incurred due to any delay in shipment of goods hereunder. Taxes are excluded unless
                                otherwise stated.</li>
                        </ul>
                    </div><!-- .tm_note -->
                </div>
            </div>

        </div>
    </div>

</body>

</html>
