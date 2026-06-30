<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/daterangepicker/daterangepicker.css') ?>">
</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm  layout-fixed <?php echo  $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">
    <div class="wrapper">


        <?= $this->include('template/header') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('template/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Main content -->

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- ./col -->
                        <div class="col-md-12">
                            <div class="card card-<?= $settings['primary_color'] ?>">
                                <div class="card-header">
                                    <h3 class="card-title"> Order Action Section</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="d-flex ">
                                        <form action="">
                                            <div class="mr-2">

                                                <select id="delivery_boy_id" onchange="assignDeliveryBoy(this)" class=" form-control form-control-sm  primary-bprder filter-product">
                                                    <option value="" aria-readonly="true" readonly>Assign Delivery Boy</option>
                                                    <?php foreach ($delivery_boy_lists as $delivery_boy_list):
                                                    ?>
                                                        <option value="<?= esc($delivery_boy_list['id']);
                                                                        ?>" <?php echo $orderDetails['delivery_boy_id'] ==  $delivery_boy_list['id'] ? 'selected' : '' ?>><?= esc($delivery_boy_list['name']);
                                                                                                                                                                            ?></option>
                                                    <?php endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="mx-2">
                                            <select id="status" onchange="updateStatus(this)" class="form-control form-control-sm primary-bprder filter-product">
                                                <option value="" disabled>Update Status</option>
                                                <?php foreach ($status_list as $orderStatusList):
                                                ?>
                                                    <option value="<?= esc($orderStatusList['id']);
                                                                    ?>" <?php echo $orderDetails['status'] ==  $orderStatusList['id'] ? 'selected' : '' ?>><?= esc($orderStatusList['status']);
                                                                                                                                                            ?></option>
                                                <?php endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class=" mx-2">
                                            <a id="download-invoice" class="btn btn-primary btn-sm">
                                                <i class="fi fi-tr-file-export"></i> Export Invoice PDF
                                            </a>
                                        </div>
                                        <div class=" mx-2">
                                            <a onclick="printDiv('invoice')" rel="noopener" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fi fi-tr-print"></i> Print Invoice
                                            </a>
                                        </div>
                                        <div class=" mx-2">
                                            <a onclick="printThermal()" rel="noopener" target="_blank" class="btn btn-secondary btn-sm">
                                                <i class="fi fi-tr-receipt"></i> Thermal Print
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <?php 
                            if($orderDetails['status'] == 7){ ?>
                                <div class="alert alert-default-danger">
                                    <h6>Cancellation Note: <b><?= $orderDetails['note'] ?></b></h6>
                            </div>
                            <?php }
                            ?>
                            

                            <div class="card card-<?= $settings['primary_color'] ?>">
                                <div class="card-header">
                                    <h3 class="card-title">View Order Details</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="invoice p-3 mb-3" id="invoice">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>
                                                    <img src="<?= base_url($settings['logo']) ?>" alt="" style="width: 50px;"> <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?>
                                                    <small class="float-right" id="order_date">Date: <?= date('jS M, Y', strtotime($orderDetails['order_date'])) ?></small>
                                                </h4>
                                            </div>
                                        </div> 
                                        <div class="row invoice-info">
                                            <div class="col-sm-4 invoice-col">
                                                <b>From,</b>
                                                <address>
                                                    <strong><?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></strong><br>
                                                    <? echo json_decode($settings['address'], true)['address']; ?><br>
                                                    Phone: <?= $settings['phone']; ?><br>
                                                    Email: <?= $settings['email']; ?><br>
                                                    Website: <?= base_url(); ?>
                                                    <?php if (!empty($settings['company_gst'])): ?>
                                                    <br><strong>GST No:</strong> <?= esc($settings['company_gst']) ?>
                                                    <?php endif; ?>
                                                </address>
                                            </div>
                                            <div class="col-sm-4 invoice-col">
                                                <b>Shipping Address</b>
                                                <address>
                                                    <strong id="name"><?= $orderDetails['user_name'] ?></strong><br>
                                                    <span id="address"><?= $orderDetails['address'] . ", " . $orderDetails['area'] . ", " . $orderDetails['city'] . ", " . $orderDetails['state'] . "-" . $orderDetails['pincode'] ?></span><br>
                                                    Phone: <span id="phone"><?= $orderDetails['user_mobile'] ?></span><br>
                                                    Email: <span id="mail_id"><?= $orderDetails['user_email'] ?></span>
                                                    <?php if (!empty($orderDetails['billing_gst'])): ?>
                                                    <br><strong>GST No:</strong> <span id="billing_gst"><?= esc($orderDetails['billing_gst']) ?></span>
                                                    <?php endif; ?>
                                                </address>
                                            </div>
                                            <div class="col-sm-4 invoice-col">
                                                <b>Invoice #<span id="invoice_id"><?= $orderDetails['order_id'] ?></span></b><br>
                                                <br>
                                                <b>Order ID:</b> <span id="order_id"><?= $orderDetails['user_order_id'] ?></span><br>
                                                <b>Delivery Date:</b> <span id="Delivery_date"><?= date('jS M, Y', strtotime($orderDetails['delivery_date'])) ?></span><br>
                                                <b>Time Slot:</b> <span id="time_slot"><?= $orderDetails['timeslot'] ?></span><br>
                                                <b>Order Status:</b> <span id="order_status" class="badge <?= $orderDetails['order_status_color'] ?>"><?= $orderDetails['order_status'] ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <?php
                                                // Collect unique tax names (grouped by name only — not by slab)
                                                $allUniqueTaxes = [];
                                                if (!empty($taxBreakdowns)) {
                                                    foreach ($taxBreakdowns as $opId => $breakdowns) {
                                                        foreach ($breakdowns as $tb) {
                                                            $tKey = $tb['tax_name'];
                                                            if (!isset($allUniqueTaxes[$tKey])) {
                                                                $allUniqueTaxes[$tKey] = $tb['tax_name'];
                                                            }
                                                        }
                                                    }
                                                }
                                                $taxColCount = count($allUniqueTaxes) * 2;
                                                $totalColCount = 6 + $taxColCount + 1;
                                                ?>
                                                <table class="table table-striped" id="view_order_list" style="width: 100%;" data-ordering="false">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr.&nbsp;No.</th>
                                                            <th>Item</th>
                                                            <th>MRP</th>
                                                            <th>Discount</th>
                                                            <th>Qty</th>
                                                            <th>Taxable&nbsp;Amt</th>
                                                            <?php foreach ($allUniqueTaxes as $tKey => $tName): ?>
                                                                <th style="font-size:12px;"><?= esc($tName); ?>&nbsp;(%)</th>
                                                                <th style="font-size:12px;"><?= esc($tName); ?>&nbsp;(Amt)</th>
                                                            <?php endforeach; ?>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($orderProducts)) : ?>
                                                            <?php foreach ($orderProducts as $index => $product) : ?>
                                                                <?php
                                                                    $mrp = (float)$product['price'];
                                                                    $sellingPrice = ($product['discounted_price'] > 0) ? (float)$product['discounted_price'] : $mrp;
                                                                    $discountTotal = ($mrp - $sellingPrice) * $product['quantity'];
                                                                    $lineTotal = $sellingPrice * $product['quantity'];

                                                                    $productTaxBreakdown = isset($taxBreakdowns[$product['order_product_id']]) ? $taxBreakdowns[$product['order_product_id']] : [];
                                                                    $totalBreakdownTax = 0;
                                                                    $taxByKey = [];
                                                                    foreach ($productTaxBreakdown as $tb) {
                                                                        $totalBreakdownTax += (float)$tb['tax_amount'];
                                                                        $taxByKey[$tb['tax_name']] = [
                                                                            'percentage' => $tb['tax_percentage'],
                                                                            'amount'     => (float)$tb['tax_amount'],
                                                                        ];
                                                                    }

                                                                    $isInclusive = ((float)$product['tax_amount'] == 0 && $totalBreakdownTax > 0);
                                                                    $taxableAmt = $isInclusive ? ($lineTotal - $totalBreakdownTax) : $lineTotal;
                                                                    $rowTotal = $lineTotal + (float)$product['tax_amount'];
                                                                ?>
                                                                <tr>
                                                                    <td><?= $index + 1; ?></td>
                                                                    <td><a class="text-dark text-underline" href="/admin/product/view/<?= $product['product_id'] ?>"><?= htmlspecialchars($product['product_name'] . ' (' . $product['product_variant_name'] . ')'); ?></a></td>
                                                                    <td><?= number_format($mrp, 2); ?></td>
                                                                    <td><?= number_format($discountTotal, 2); ?></td>
                                                                    <td><?= $product['quantity']; ?></td>
                                                                    <td><?= number_format($taxableAmt, 2); ?></td>
                                                                    <?php foreach ($allUniqueTaxes as $tKey => $tName): ?>
                                                                        <td><?= isset($taxByKey[$tName]) ? number_format($taxByKey[$tName]['percentage'], 2) . '%' : '-'; ?></td>
                                                                        <td><?= isset($taxByKey[$tName]) ? number_format($taxByKey[$tName]['amount'], 2) : '0.00'; ?></td>
                                                                    <?php endforeach; ?>
                                                                    <td><?= number_format($rowTotal, 2); ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <tr>
                                                                <td colspan="<?= $totalColCount ?>" class="text-center">No products found for this order.</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <b>Payment Mode:</b> <br>
                                                <img src="<?= base_url().$orderDetails['payment_method_img'] ?>" id="payment_img" style="width: 70px;">
                                                <span id="method"><?= $orderDetails['payment_method_title'] ?></span><br>
                                                <b>Payment Id:</b> <span id="payment_id"><?= $orderDetails['transaction_id'] ?></span>

                                                <?php if (!empty($orderDetails['delivery_instruction'])): ?>
                                                <div style="margin-top:12px; padding:10px 12px; background:#fffbeb; border:1px solid #fcd34d; border-radius:6px;">
                                                    <b style="font-size:12px; color:#92400e;">&#128203; Delivery Instructions:</b><br>
                                                    <span style="font-size:12px; color:#78350f;"><?= esc($orderDetails['delivery_instruction']) ?></span>
                                                </div>
                                                <?php endif; ?>

                                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

                                                </p>
                                            </div>
                                            <div class="col-6">

                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <th style="width:50%">Subtotal : </th>
                                                            <td><?php if ($settings['currency_symbol_position'] == 'left') {
                                                                    echo $country['currency_symbol'];
                                                                } ?> <span id="subtotal"><?= $subtotalOfOrder['subtotal'] ?></span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                                                                                    echo $country['currency_symbol'];
                                                                                                                                } ?></td>
                                                        </tr>
                                                        <?php
                                                        // Aggregate tax breakdown across all products
                                                        $taxSummary = [];
                                                        if (!empty($taxBreakdowns)) {
                                                            foreach ($taxBreakdowns as $opId => $breakdowns) {
                                                                foreach ($breakdowns as $tb) {
                                                                    $key = $tb['tax_name'] . '_' . $tb['tax_percentage'];
                                                                    if (!isset($taxSummary[$key])) {
                                                                        $taxSummary[$key] = ['name' => $tb['tax_name'], 'percentage' => $tb['tax_percentage'], 'amount' => 0];
                                                                    }
                                                                    $taxSummary[$key]['amount'] += $tb['tax_amount'];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <?php if (!empty($taxSummary)): ?>
                                                            <?php foreach ($taxSummary as $ts): ?>
                                                            <tr>
                                                                <th><?= esc($ts['name']); ?> (<?= $ts['percentage']; ?>%) :</th>
                                                                <td><?php if ($settings['currency_symbol_position'] == 'left') { echo $country['currency_symbol']; } ?> <?= number_format($ts['amount'], 2); ?> <?php if ($settings['currency_symbol_position'] == 'right') { echo $country['currency_symbol']; } ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <tr>
                                                            <th>Total Tax : </th>
                                                            <td><?php if ($settings['currency_symbol_position'] == 'left') {
                                                                    echo $country['currency_symbol'];
                                                                } ?> <span id="tax_value"><?= $orderDetails['tax'] ?> </span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                                                                                echo $country['currency_symbol'];
                                                                                                                            } ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Delivery :</th>
                                                            <td><?php if ($settings['currency_symbol_position'] == 'left') {
                                                                    echo $country['currency_symbol'];
                                                                } ?> <span id="delivery_charge"><?= $orderDetails['delivery_charge'] ?></span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                                                                                                    echo $country['currency_symbol'];
                                                                                                                                                } ?> </td>
                                                        </tr>
                                                        <?php
                                                        if ($settings['additional_charge_status']):
                                                        ?>
                                                            <tr>
                                                                <th><?= $settings['additional_charge_name'] ?> :</th>
                                                                <td><?php if ($settings['currency_symbol_position'] == 'left') {
                                                                        echo $country['currency_symbol'];
                                                                    } ?> <span id="additional_charge"><?= $orderDetails['additional_charge'] ?> </span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                                                                                                            echo $country['currency_symbol'];
                                                                                                                                                        } ?> </td>
                                                            </tr>
                                                        <?php endif ?>
                                                        <tr>
                                                            <th>Discount <span id="coupon_code"> </span>:</th>
                                                            <td>- <?php if ($settings['currency_symbol_position'] == 'left') {
                                                                        echo $country['currency_symbol'];
                                                                    } ?> <span id="total_discount"><?= $orderDetails['coupon_amount'] ?> </span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                                                                                                    echo $country['currency_symbol'];
                                                                                                                                                } ?> </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Wallet <span id="wallet"> </span>:</th>
                                                            <td>- <?php if ($settings['currency_symbol_position'] == 'left') {
                                                                        echo $country['currency_symbol'];
                                                                    } ?> <span id="used_wallet_amount"><?= $orderDetails['used_wallet_amount'] ?></span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                                                                                                            echo $country['currency_symbol'];
                                                                                                                                                        } ?> </td>
                                                        </tr>
                                                        <?php if (!empty($orderDetails['delivery_tip_amount']) && $orderDetails['delivery_tip_amount'] > 0): ?>
                                                        <tr>
                                                            <th style="color:#16a34a;">&#9829; Delivery Tip :</th>
                                                            <td style="color:#16a34a;"><?php if ($settings['currency_symbol_position'] == 'left') {
                                                                    echo $country['currency_symbol'];
                                                                } ?> <span id="delivery_tip"><?= number_format($orderDetails['delivery_tip_amount'], 2) ?></span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                    echo $country['currency_symbol'];
                                                                } ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <tr>
                                                            <th>Total:</th>
                                                            <?php $adminTip = !empty($orderDetails['delivery_tip_amount']) ? (float)$orderDetails['delivery_tip_amount'] : 0; ?>
                                                            <td><?php if ($settings['currency_symbol_position'] == 'left') {
                                                                    echo $country['currency_symbol'];
                                                                } ?> <span id="total"><?= round($subtotalOfOrder['subtotal'] + $orderDetails['additional_charge'] + $orderDetails['tax'] - $orderDetails['used_wallet_amount'] + $orderDetails['delivery_charge'] + $adminTip - $orderDetails['coupon_amount'], 2) ?> </span> <?php if ($settings['currency_symbol_position'] == 'right') {
                                                                    echo $country['currency_symbol'];
                                                                } ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $chargeTaxBreakdowns = $chargeTaxBreakdowns ?? [];
                                        $allCTNamesOD = [];
                                        foreach ($chargeTaxBreakdowns as $taxes) {
                                            foreach ($taxes as $t) {
                                                if (!isset($allCTNamesOD[$t['tax_name']])) {
                                                    $allCTNamesOD[$t['tax_name']] = $t['tax_name'];
                                                }
                                            }
                                        }
                                        $hasODCharges = (!empty($orderDetails['delivery_charge']) && $orderDetails['delivery_charge'] > 0)
                                            || (!empty($orderDetails['additional_charge']) && $orderDetails['additional_charge'] > 0)
                                            || (!empty($orderDetails['delivery_tip_amount']) && $orderDetails['delivery_tip_amount'] > 0);
                                        ?>
                                        <?php if ($hasODCharges): ?>
                                        <div style="border:1px solid #e0e0e0; border-radius:6px; overflow:hidden; margin-top:16px;">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; font-size:12px;">
                                                <thead>
                                                    <tr style="background:#f5f5f5; border-bottom:1px solid #e0e0e0;">
                                                        <th colspan="<?= 2 + count($allCTNamesOD) * 2 ?>" style="font-size:11px; font-weight:700; color:#374151; padding:8px 12px; text-align:left; border:none;">Charges &amp; Fees</th>
                                                    </tr>
                                                    <tr style="background:#fafafa; border-bottom:1px solid #e0e0e0;">
                                                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:left; border:none;">Description</th>
                                                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:right; border:none;">Amount</th>
                                                        <?php foreach ($allCTNamesOD as $txName): ?>
                                                            <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:right; border:none;"><?= esc($txName) ?>&nbsp;(%)</th>
                                                            <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:right; border:none;"><?= esc($txName) ?>&nbsp;(Amt)</th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($orderDetails['delivery_charge']) && $orderDetails['delivery_charge'] > 0): ?>
                                                    <?php $dcTOD = $chargeTaxBreakdowns['delivery'] ?? []; $dcByNmOD = array_column($dcTOD, null, 'tax_name'); ?>
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:7px 12px; color:#374151;">Delivery Charge</td>
                                                        <td style="padding:7px 12px; text-align:right; color:#374151;"><?= number_format($orderDetails['delivery_charge'], 2) ?></td>
                                                        <?php foreach ($allCTNamesOD as $txName): ?>
                                                            <td style="padding:7px 12px; text-align:right; color:#6b7280;"><?= isset($dcByNmOD[$txName]) ? number_format($dcByNmOD[$txName]['tax_percentage'], 2) . '%' : '-' ?></td>
                                                            <td style="padding:7px 12px; text-align:right; color:#374151;"><?= isset($dcByNmOD[$txName]) ? number_format($dcByNmOD[$txName]['tax_amount'], 2) : '0.00' ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if (!empty($orderDetails['additional_charge']) && $orderDetails['additional_charge'] > 0): ?>
                                                    <?php $acTOD = $chargeTaxBreakdowns['additional'] ?? []; $acByNmOD = array_column($acTOD, null, 'tax_name'); ?>
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:7px 12px; color:#374151;"><?= esc($settings['additional_charge_name'] ?? 'Additional Charge') ?></td>
                                                        <td style="padding:7px 12px; text-align:right; color:#374151;"><?= number_format($orderDetails['additional_charge'], 2) ?></td>
                                                        <?php foreach ($allCTNamesOD as $txName): ?>
                                                            <td style="padding:7px 12px; text-align:right; color:#6b7280;"><?= isset($acByNmOD[$txName]) ? number_format($acByNmOD[$txName]['tax_percentage'], 2) . '%' : '-' ?></td>
                                                            <td style="padding:7px 12px; text-align:right; color:#374151;"><?= isset($acByNmOD[$txName]) ? number_format($acByNmOD[$txName]['tax_amount'], 2) : '0.00' ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if (!empty($orderDetails['delivery_tip_amount']) && $orderDetails['delivery_tip_amount'] > 0): ?>
                                                    <?php $tipTOD = $chargeTaxBreakdowns['tip'] ?? []; $tipByNmOD = array_column($tipTOD, null, 'tax_name'); ?>
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:7px 12px; color:#374151;">Delivery Tip</td>
                                                        <td style="padding:7px 12px; text-align:right; color:#374151;"><?= number_format($orderDetails['delivery_tip_amount'], 2) ?></td>
                                                        <?php foreach ($allCTNamesOD as $txName): ?>
                                                            <td style="padding:7px 12px; text-align:right; color:#6b7280;"><?= isset($tipByNmOD[$txName]) ? number_format($tipByNmOD[$txName]['tax_percentage'], 2) . '%' : '-' ?></td>
                                                            <td style="padding:7px 12px; text-align:right; color:#374151;"><?= isset($tipByNmOD[$txName]) ? number_format($tipByNmOD[$txName]['tax_amount'], 2) : '0.00' ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endif; ?>
                                        <?php
                                        $combinedTaxSummaryOD = [];
                                        foreach ($taxSummary as $ts) {
                                            $k = $ts['name'] . '_' . $ts['percentage'];
                                            if (!isset($combinedTaxSummaryOD[$k])) {
                                                $combinedTaxSummaryOD[$k] = ['name' => $ts['name'], 'percentage' => $ts['percentage'], 'amount' => 0];
                                            }
                                            $combinedTaxSummaryOD[$k]['amount'] += $ts['amount'];
                                        }
                                        foreach ($chargeTaxBreakdowns as $ctTaxesOD) {
                                            foreach ($ctTaxesOD as $ct) {
                                                $k = $ct['tax_name'] . '_' . $ct['tax_percentage'];
                                                if (!isset($combinedTaxSummaryOD[$k])) {
                                                    $combinedTaxSummaryOD[$k] = ['name' => $ct['tax_name'], 'percentage' => $ct['tax_percentage'], 'amount' => 0];
                                                }
                                                $combinedTaxSummaryOD[$k]['amount'] += (float)$ct['tax_amount'];
                                            }
                                        }
                                        $grandTaxTotalOD = array_sum(array_column($combinedTaxSummaryOD, 'amount'));
                                        $symOD = $country['currency_symbol'];
                                        $symLeftOD = ($settings['currency_symbol_position'] == 'left');
                                        ?>
                                        <?php if (!empty($combinedTaxSummaryOD)): ?>
                                        <div style="border:1px solid #e0e0e0; border-radius:6px; overflow:hidden; margin-top:12px;">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; font-size:12px;">
                                                <thead>
                                                    <tr style="background:#f5f5f5; border-bottom:1px solid #e0e0e0;">
                                                        <th colspan="3" style="font-size:11px; font-weight:700; color:#374151; padding:8px 12px; text-align:left; border:none;">Tax Summary</th>
                                                    </tr>
                                                    <tr style="background:#fafafa; border-bottom:1px solid #e0e0e0;">
                                                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:left; border:none;">Tax Name</th>
                                                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:center; border:none;">Rate</th>
                                                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:6px 12px; text-align:right; border:none;">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($combinedTaxSummaryOD as $cts): ?>
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:7px 12px; color:#374151;"><?= esc($cts['name']) ?></td>
                                                        <td style="padding:7px 12px; text-align:center; color:#6b7280;"><?= number_format($cts['percentage'], 2) ?>%</td>
                                                        <td style="padding:7px 12px; text-align:right; color:#374151;"><?= $symLeftOD ? $symOD . number_format($cts['amount'], 2) : number_format($cts['amount'], 2) . $symOD ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <tr style="background:#f5f5f5; border-top:1px solid #e0e0e0;">
                                                        <td colspan="2" style="font-size:12px; font-weight:700; color:#1a1a2e; padding:7px 12px;">Total Tax</td>
                                                        <td style="font-size:12px; font-weight:700; color:#1a1a2e; padding:7px 12px; text-align:right;"><?= $symLeftOD ? $symOD . number_format($grandTaxTotalOD, 2) : number_format($grandTaxTotalOD, 2) . $symOD ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endif; ?>

                                        <div style="border-top: 1px solid #e0e0e0; background: #fafafa; padding: 10px 12px; margin-top: 2px; font-size: 11px;">
                                            <div style="font-weight: 700; color: #1a1a2e; margin-bottom: 3px;">Whether the tax is payable on reverse charge - No</div>
                                            <div style="color: #888; font-size: 10px; margin-top: 4px;">Bill Generated by <strong style="color: #1a1a2e;"><?= $settings['business_name'] ?></strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>

        <!-- /.content-wrapper -->
        <?= $this->include('template/footer') ?>

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <?= $this->include('template/script') ?>
    <script src="<?= base_url('/assets/page-script/orders.js') ?>"></script>
    <script>
        let previousDeliveryBoyId = null;
        let previousStatus = null;

        previousDeliveryBoyId = document.getElementById('delivery_boy_id').value;
        previousStatus = document.getElementById('status').value;

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        $("#download-invoice").on('click', function() {
            $.ajax({
                url: "/admin/orders/download_invoice",
                type: "POST",
                data: {
                    invoice: "<?= $orderDetails['order_id'] ?>",
                },
                xhrFields: {
                    responseType: 'blob' // Expect the response as a Blob
                },
                beforeSend: function() {
                    $("#download-invoice").html(`<i class="fi fi-tr-loading spin-icon"></i>  Downloading Invoice...`);
                    $("#download-invoice").attr(`disabled`, `disabled`);
                },
                success: function(blob) {
                    // Create a download link for the PDF
                    const link = document.createElement('a');
                    const url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = `order_invoice_<?= $orderDetails['order_id'] ?>.pdf`;
                    document.body.appendChild(link);
                    link.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(link);
                },
                complete: function() {
                    $("#download-invoice").html(' <i class="fi fi-tr-file-export"></i> Export Invoice PDF');
                    $("#download-invoice").removeAttr(`disabled`);

                },
                error: function(xhr) {
                    console.error("Error generating PDF:", xhr.responseText);
                    alert("Failed to download invoice. Please try again.");
                }
            });


        })

        function updateStatus(status) {
            const newValue = status.value;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    previousStatus = newValue;

                    $.ajax({
                        url: "/admin/orders/update_status",
                        type: "POST",
                        data: {
                            order_id: "<?= $orderDetails['order_id'] ?>",
                            status: $("#status").val()
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Admin says');
                            } else {
                                toastr.error(response.message, 'Admin says');
                                Array.from(status.options).forEach(option => {
                                    if (option.value === previousStatus) {
                                        option.selected = true;
                                    } else {
                                        option.selected = false;
                                    }
                                });

                            }
                        },
                        complete: function() {},
                        error: function(xhr) {
                            console.error("Error generating PDF:", xhr.responseText);
                            Array.from(status.options).forEach(option => {
                                if (option.value === previousStatus) {
                                    option.selected = true;
                                } else {
                                    option.selected = false;
                                }
                            });
                            toastr.error('Something went wrong. Please try again.', 'Admin says');
                        }
                    });
                } else {
                    toastr.error('Your action has been cancelled.', 'Admin says');
                    Array.from(status.options).forEach(option => {
                        if (option.value === previousStatus) {
                            option.selected = true;
                        } else {
                            option.selected = false;
                        }
                    });

                }
            });
        }


        function printPos(divName) {
            var url = "<?= base_url('admin/pos/printInvoice?order_id=' . $orderDetails['order_id']) ?>";
            window.open(url, '_blank', 'height=800,width=500');
        }

        function printThermal() {
            var url = "<?= base_url('admin/orders/thermalPrint?order_id=' . $orderDetails['order_id']) ?>";
            window.open(url, '_blank', 'height=800,width=500');
        }

        function assignDeliveryBoy(delivery_boy_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/admin/orders/assignDeliveryBoy",
                        type: "POST",
                        data: {
                            order_id: "<?= $orderDetails['order_id'] ?>",
                            delivery_boy_id: $("#delivery_boy_id").val()
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Admin says');
                            } else {
                                delivery_boy_id.value = previousDeliveryBoyId;
                                toastr.error(response.message, 'Admin says');
                            }
                        },
                        complete: function() {},
                        error: function(xhr) {
                            delivery_boy_id.value = previousDeliveryBoyId;
                            console.error("Error generating PDF:", xhr.responseText);
                            toastr.error("Something went wrong. Please try again.", 'Admin says');
                        }
                    });
                } else {
                    delivery_boy_id.value = previousDeliveryBoyId;
                    toastr.error('Your action has been cancelled.', 'Admin says');
                }
            });
        }
    </script>


</body>

</html>