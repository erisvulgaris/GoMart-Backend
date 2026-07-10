<?php
$invTip   = !empty($orderDetails['delivery_tip_amount']) ? (float)$orderDetails['delivery_tip_amount'] : 0;
$symLeft  = ($settings['currency_symbol_position'] == 'left');
$sym      = $country['currency_symbol'];

function inv_fmt($sym, $symLeft, $value) {
    // Convert multi-byte currency symbol to HTML numeric entity so DomPDF renders it correctly
    $safeSymbol = '';
    foreach (mb_str_split($sym) as $char) {
        $code = mb_ord($char, 'UTF-8');
        $safeSymbol .= ($code > 127) ? '&#' . $code . ';' : $char;
    }
    return $symLeft ? $safeSymbol . number_format((float)$value, 2) : number_format((float)$value, 2) . $safeSymbol;
}

$totalProductPrice = 0;
$totalProductTax   = 0;
foreach ($orderProducts as $p) {
    $totalProductPrice += (($p['discounted_price'] > 0) ? $p['discounted_price'] : $p['price']) * $p['quantity'];
    $totalProductTax   += $p['tax_amount'];
}

$invTotal = round(
    $totalProductPrice
    + $totalProductTax
    + (float)$orderDetails['delivery_charge']
    + (float)$orderDetails['additional_charge']
    + $invTip
    - (float)$orderDetails['coupon_amount']
    - (float)$orderDetails['used_wallet_amount'],
2);
?>

<div id="invoice" style="font-family:'Segoe UI',Arial,sans-serif; color:#1a1a2e; background:#fff; max-width:900px; margin:0 auto;">

    <!-- ══ HEADER ══ -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 55%,#0f3460 100%); border-radius:12px 12px 0 0; padding:0; margin:0;">
        <tr>
            <td style="padding:24px 28px; vertical-align:middle;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align:middle; padding-right:14px;">
                            <img src="<?= base_url($settings['logo']) ?>" alt="Logo"
                                 style="width:54px; height:54px; object-fit:contain; background:rgba(255,255,255,0.12); border-radius:10px; padding:5px; display:block;">
                        </td>
                        <td style="vertical-align:middle;">
                            <div style="font-size:18px; font-weight:700; color:#111111; line-height:1.2;"><?= esc($settings['business_name']) ?></div>
                            <div style="font-size:10px; color:#111111; margin-top:3px;"><?= base_url() ?></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding:24px 28px; text-align:right; vertical-align:middle;">
                <div style="font-size:28px; font-weight:800; color:#111111; letter-spacing:3px; text-transform:uppercase; line-height:1;"><?php echo lang('website.invoice'); ?></div>
                <div style="font-size:12px; color:#111111; margin-top:6px;"># <?= esc($orderDetails['order_id']) ?></div>
            </td>
        </tr>
    </table>

    <!-- ══ META: FROM / SHIP TO / ORDER INFO ══ -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8eaf0; border-top:none; background:#f8f9fc;">
        <tr>
            <!-- FROM -->
            <td width="33%" style="padding:18px 20px; vertical-align:top; border-right:1px solid #e8eaf0;">
                <div style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#7b8499; margin-bottom:10px;">
                    <i class="fi fi-rr-shop" style="margin-right:4px;"></i> <?php echo lang('website.from'); ?>
                </div>
                <div style="font-weight:700; font-size:12px; color:#1a1a2e; margin-bottom:4px;"><?= esc($settings['business_name']) ?></div>
                <div style="font-size:11px; color:#4b5563; line-height:1.7;">
                    <?php
                        $addr = json_decode($settings['address'], true);
                        echo isset($addr['address']) ? esc($addr['address']) : '';
                    ?><br>
                    <i class="fi fi-rr-phone-call" style="color:#7b8499;"></i> <?= esc($settings['phone']) ?><br>
                    <i class="fi fi-rr-envelope" style="color:#7b8499;"></i> <?= esc($settings['email']) ?>
                    <?php if (!empty($settings['company_gst'])): ?>
                    <br><i class="fi fi-rr-document-signed" style="color:#7b8499;"></i> <strong>GST:</strong> <?= esc($settings['company_gst']) ?>
                    <?php endif; ?>
                </div>
            </td>

            <!-- SHIP TO -->
            <td width="33%" style="padding:18px 20px; vertical-align:top; border-right:1px solid #e8eaf0;">
                <div style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#7b8499; margin-bottom:10px;">
                    <i class="fi fi-rr-marker" style="margin-right:4px;"></i> <?php echo lang('website.shipping_address'); ?>
                </div>
                <div style="font-weight:700; font-size:12px; color:#1a1a2e; margin-bottom:4px;" id="name"><?= esc(!empty($orderDetails['recipient_name']) ? $orderDetails['recipient_name'] : $orderDetails['user_name']) ?></div>
                <div style="font-size:11px; color:#4b5563; line-height:1.7;">
                    <span id="address"><?= esc($orderDetails['address'] . ', ' . $orderDetails['area'] . ', ' . $orderDetails['city'] . ', ' . $orderDetails['state'] . ' - ' . $orderDetails['pincode']) ?></span><br>
                    <i class="fi fi-rr-phone-call" style="color:#7b8499;"></i> <span id="phone"><?= esc(!empty($orderDetails['recipient_mobile']) ? $orderDetails['recipient_mobile'] : $orderDetails['user_mobile']) ?></span><br>
                    <i class="fi fi-rr-envelope" style="color:#7b8499;"></i> <span id="mail_id"><?= esc($orderDetails['user_email']) ?></span>
                    <?php if (!empty($orderDetails['billing_gst'])): ?>
                    <br><i class="fi fi-rr-document-signed" style="color:#7b8499;"></i> <strong>GST:</strong> <?= esc($orderDetails['billing_gst']) ?>
                    <?php endif; ?>
                </div>
            </td>

            <!-- ORDER DETAILS -->
            <td width="34%" style="padding:18px 20px; vertical-align:top;">
                <div style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#7b8499; margin-bottom:10px;">
                    <i class="fi fi-rr-file-invoice" style="margin-right:4px;"></i> Order Details
                </div>
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="font-size:11px; color:#7b8499; padding:2px 0; white-space:nowrap; padding-right:8px;"><?php echo lang('website.order_id'); ?></td>
                        <td style="font-size:11px; font-weight:600; color:#1a1a2e; padding:2px 0;" id="order_id">#<?= esc($orderDetails['user_order_id']) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:11px; color:#7b8499; padding:2px 0; white-space:nowrap; padding-right:8px;"><?php echo lang('website.order_date'); ?></td>
                        <td style="font-size:11px; font-weight:600; color:#1a1a2e; padding:2px 0;" id="Order_date"><?= date('jS M, Y', strtotime($orderDetails['order_date'])) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:11px; color:#7b8499; padding:2px 0; white-space:nowrap; padding-right:8px;"><?php echo lang('website.delivery_date'); ?></td>
                        <td style="font-size:11px; font-weight:600; color:#1a1a2e; padding:2px 0;" id="Delivery_date">
                            <?= isset($orderDetails['delivery_date']) && $orderDetails['delivery_date'] ? date('jS M, Y', strtotime($orderDetails['delivery_date'])) : '&mdash;' ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:11px; color:#7b8499; padding:2px 0; white-space:nowrap; padding-right:8px;"><?php echo lang('website.time_slot'); ?></td>
                        <td style="font-size:11px; font-weight:600; color:#1a1a2e; padding:2px 0;" id="time_slot"><?= esc($orderDetails['timeslot']) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:11px; color:#7b8499; padding:2px 0; white-space:nowrap; padding-right:8px;"><?php echo lang('website.order_status'); ?></td>
                        <td style="padding:2px 0;">
                            <span id="order_status" class="badge badge-sm <?= $orderDetails['order_status_color'] ?>" style="font-size:10px;"><?= esc($orderDetails['order_status']) ?></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ══ PRODUCT TABLE ══ -->
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
    $thStyle = "font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.7px; color:#fff; padding:11px 10px; text-align:left; border:none;";
    $tdStyle = "font-size:11px; color:#4b5563; padding:10px 10px; border:none; vertical-align:middle;";
    ?>
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8eaf0; border-top:none;">
        <thead>
            <tr style="background:#1a1a2e;">
                <th style="<?= $thStyle ?> width:32px;">#</th>
                <th style="<?= $thStyle ?>"><?php echo lang('website.product'); ?></th>
                <th style="<?= $thStyle ?>">MRP</th>
                <th style="<?= $thStyle ?>"><?php echo lang('website.discount'); ?></th>
                <th style="<?= $thStyle ?>"><?php echo lang('website.quantity'); ?></th>
                <th style="<?= $thStyle ?>">Taxable Amt</th>
                <?php foreach ($allUniqueTaxes as $tKey => $tName): ?>
                    <th style="<?= $thStyle ?>"><?= esc($tName); ?>&nbsp;(%) & Amt</th>
                <?php endforeach; ?>
                <th style="<?= $thStyle ?> text-align:right;"><?php echo lang('website.subtotal'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orderProducts)): ?>
                <?php foreach ($orderProducts as $index => $product):
                    $mrp = (float)$product['price'];
                    $sellingPrice = ($product['discounted_price'] > 0) ? (float)$product['discounted_price'] : $mrp;
                    $discountTotal = ($mrp - $sellingPrice) * $product['quantity'];
                    $lineTotal = $sellingPrice * $product['quantity'];
                    $rowBg = ($index % 2 === 0) ? '#fff' : '#f8f9fc';

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

                    $isInclusive = ((float)$product['tax_amount'] == 0 && (float)$product['tax_percentage'] > 0);
                    if ($isInclusive && $totalBreakdownTax == 0 && !empty($productTaxBreakdown)) {
                        $totalPct = array_sum(array_column($productTaxBreakdown, 'tax_percentage'));
                        if ($totalPct > 0) {
                            $baseForTax = $lineTotal / (1 + $totalPct / 100);
                            foreach ($productTaxBreakdown as $tb) {
                                $amt = round($baseForTax * (float)$tb['tax_percentage'] / 100, 2);
                                $totalBreakdownTax += $amt;
                                $taxByKey[$tb['tax_name']] = [
                                    'percentage' => $tb['tax_percentage'],
                                    'amount'     => $amt,
                                ];
                            }
                        }
                    }
                    $taxableAmt = $isInclusive ? ($lineTotal - $totalBreakdownTax) : $lineTotal;
                    $rowTotal = $lineTotal + (float)$product['tax_amount'];
                ?>
                <tr style="background:<?= $rowBg ?>; border-bottom:1px solid #f0f2f7;">
                    <td style="<?= $tdStyle ?> color:#9ca3af;"><?= $index + 1 ?></td>
                    <td style="padding:10px 10px; border:none; vertical-align:middle;">
                        <div style="font-size:12px; font-weight:600; color:#1a1a2e;"><?= esc($product['product_name']) ?></div>
                        <div style="font-size:10px; color:#7b8499; margin-top:2px;"><?= esc($product['product_variant_name']) ?></div>
                    </td>
                    <td style="<?= $tdStyle ?>"><?= number_format($mrp, 2) ?></td>
                    <td style="<?= $tdStyle ?>"><?= number_format($discountTotal, 2) ?></td>
                    <td style="<?= $tdStyle ?>"><?= $product['quantity'] ?></td>
                    <td style="<?= $tdStyle ?>"><?= number_format($taxableAmt, 2) ?></td>
                    <?php foreach ($allUniqueTaxes as $tKey => $tName): ?>
                        <td style="<?= $tdStyle ?>"><?= isset($taxByKey[$tName]) ? number_format($taxByKey[$tName]['percentage'], 2) . '%' : '-'; ?> <?= isset($taxByKey[$tName]) ? number_format($taxByKey[$tName]['amount'], 2) : '0.00'; ?></td>
                    <?php endforeach; ?>
                    <td style="font-size:12px; font-weight:600; color:#1a1a2e; padding:10px 10px; border:none; vertical-align:middle; text-align:right;"><?= number_format($rowTotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $totalColCount ?>" style="text-align:center; padding:28px; color:#7b8499; font-size:12px; border:none;"><?php echo lang('website.no_products_found_for_this_order'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ══ FOOTER: PAYMENT + TOTALS ══ -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8eaf0; border-top:none; border-radius:0 0 12px 12px; overflow:hidden;">
        <tr>
            <!-- Payment Info -->
            <td width="50%" style="padding:20px 22px; vertical-align:top; background:#f8f9fc; border-right:1px solid #e8eaf0;">
                <div style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#7b8499; margin-bottom:10px;">
                    <i class="fi fi-rr-credit-card" style="margin-right:4px;"></i> <?php echo lang('website.payment_mode'); ?>
                </div>
                <div style="font-size:13px; font-weight:700; color:#1a1a2e;" id="method"><?= esc($orderDetails['payment_method_title']) ?></div>

                <?php if (!empty($orderDetails['delivery_instruction'])): ?>
                <div style="margin-top:16px; background:#fffbeb; border:1px solid #fcd34d; border-radius:8px; padding:12px 14px;">
                    <div style="font-size:10px; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:5px;">
                        <i class="fi fi-rr-clipboard-list" style="margin-right:4px;"></i> Delivery Instructions
                    </div>
                    <div style="font-size:11px; color:#78350f; line-height:1.6;"><?= esc($orderDetails['delivery_instruction']) ?></div>
                </div>
                <?php endif; ?>
            </td>

            <!-- Totals -->
            <td width="50%" style="padding:20px 22px; vertical-align:top; background:#fff;">
                <div style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#7b8499; margin-bottom:10px;">
                    <i class="fi fi-rr-receipt" style="margin-right:4px;"></i> Order Summary
                </div>
                <?php
                // Aggregate tax breakdown across all order products (used below in Tax Summary section)
                $taxSummary = [];
                foreach ($orderProducts as $op) {
                    $opBreakdown = isset($taxBreakdowns[$op['order_product_id']]) ? $taxBreakdowns[$op['order_product_id']] : [];
                    if (empty($opBreakdown)) continue;
                    $opMrp = (float)$op['price'];
                    $opSell = ($op['discounted_price'] > 0) ? (float)$op['discounted_price'] : $opMrp;
                    $opLineTotal = $opSell * $op['quantity'];
                    $opBreakdownSum = array_sum(array_column($opBreakdown, 'tax_amount'));
                    $opIsInclusive = ((float)$op['tax_amount'] == 0 && (float)$op['tax_percentage'] > 0);
                    if ($opIsInclusive && $opBreakdownSum == 0) {
                        $opTotalPct = array_sum(array_column($opBreakdown, 'tax_percentage'));
                        if ($opTotalPct > 0) {
                            $opBase = $opLineTotal / (1 + $opTotalPct / 100);
                            foreach ($opBreakdown as $tb) {
                                $amt = round($opBase * (float)$tb['tax_percentage'] / 100, 2);
                                $key = $tb['tax_name'] . '_' . $tb['tax_percentage'];
                                if (!isset($taxSummary[$key])) $taxSummary[$key] = ['name' => $tb['tax_name'], 'percentage' => $tb['tax_percentage'], 'amount' => 0];
                                $taxSummary[$key]['amount'] += $amt;
                            }
                        }
                    } else {
                        foreach ($opBreakdown as $tb) {
                            $key = $tb['tax_name'] . '_' . $tb['tax_percentage'];
                            if (!isset($taxSummary[$key])) $taxSummary[$key] = ['name' => $tb['tax_name'], 'percentage' => $tb['tax_percentage'], 'amount' => 0];
                            $taxSummary[$key]['amount'] += (float)$tb['tax_amount'];
                        }
                    }
                }
                ?>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:12px; color:#6b7280; padding:4px 0;"><?php echo lang('website.subtotal'); ?></td>
                        <td style="font-size:12px; font-weight:600; color:#1a1a2e; padding:4px 0; text-align:right;" id="subtotal"><?= inv_fmt($sym, $symLeft, $totalProductPrice) ?></td>
                    </tr>
                    <?php if ($totalProductTax > 0): ?>
                    <tr>
                        <td style="font-size:12px; color:#6b7280; padding:4px 0;"><?php echo lang('website.tax'); ?> <span style="font-size:10px; color:#9ca3af;">(excl.)</span></td>
                        <td style="font-size:12px; font-weight:600; color:#1a1a2e; padding:4px 0; text-align:right;" id="tax_value"><?= inv_fmt($sym, $symLeft, $totalProductTax) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="font-size:12px; color:#6b7280; padding:4px 0;"><?php echo lang('website.delivery_charge'); ?></td>
                        <td style="font-size:12px; font-weight:600; color:#1a1a2e; padding:4px 0; text-align:right;" id="delivery_charge"><?= inv_fmt($sym, $symLeft, $orderDetails['delivery_charge']) ?></td>
                    </tr>
                    <?php if (!empty($orderDetails['additional_charge']) && $orderDetails['additional_charge'] > 0): ?>
                    <tr>
                        <td style="font-size:12px; color:#6b7280; padding:4px 0;"><?= esc($settings['additional_charge_name']) ?></td>
                        <td style="font-size:12px; font-weight:600; color:#1a1a2e; padding:4px 0; text-align:right;" id="additional_charge"><?= inv_fmt($sym, $symLeft, $orderDetails['additional_charge']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($orderDetails['coupon_amount']) && $orderDetails['coupon_amount'] > 0): ?>
                    <tr>
                        <td style="font-size:12px; color:#dc2626; padding:4px 0;"><i class="fi fi-rr-ticket" style="margin-right:4px;"></i> <?php echo lang('website.discount'); ?></td>
                        <td style="font-size:12px; font-weight:600; color:#dc2626; padding:4px 0; text-align:right;" id="total_discount">- <?= inv_fmt($sym, $symLeft, $orderDetails['coupon_amount']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($orderDetails['used_wallet_amount']) && $orderDetails['used_wallet_amount'] > 0): ?>
                    <tr>
                        <td style="font-size:12px; color:#dc2626; padding:4px 0;"><i class="fi fi-rr-wallet" style="margin-right:4px;"></i> <?php echo lang('website.wallet'); ?></td>
                        <td style="font-size:12px; font-weight:600; color:#dc2626; padding:4px 0; text-align:right;" id="used_wallet_amount">- <?= inv_fmt($sym, $symLeft, $orderDetails['used_wallet_amount']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($invTip > 0): ?>
                    <tr>
                        <td style="font-size:12px; color:#16a34a; padding:4px 0;"><i class="fi fi-rr-hand-holding-heart" style="margin-right:4px;"></i> Delivery Tip</td>
                        <td style="font-size:12px; font-weight:600; color:#16a34a; padding:4px 0; text-align:right;" id="delivery_tip"><?= inv_fmt($sym, $symLeft, $invTip) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2" style="padding:6px 0;">
                            <div style="border-top:1px dashed #d1d5db;"></div>
                        </td>
                    </tr>
                    <!-- Grand Total -->
                    <tr>
                        <td colspan="2" style="padding:0;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a1a2e; border-radius:8px;">
                                <tr>
                                    <td style="font-size:14px; font-weight:800; color:#fff; padding:10px 14px;"><?php echo lang('website.total'); ?></td>
                                    <td style="font-size:14px; font-weight:800; color:#34d399; padding:10px 14px; text-align:right;" id="total"><?= inv_fmt($sym, $symLeft, $invTotal) ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ══ RETURNED PRODUCTS ══ -->
    <?php if (!empty($returnedProducts)): ?>
    <div style="border:1px solid #e8eaf0; border-top:none; background:#fff9f9; padding:18px 22px; border-radius:0 0 12px 12px; margin-top:2px;">
        <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#dc2626; margin-bottom:14px;">
            <i class="fi fi-rr-undo-alt" style="margin-right:6px;"></i><?php echo lang('website.retuned_product_list'); ?>
        </div>
        <?php
        $rThStyle = "font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#7f1d1d; padding:8px 10px; text-align:left; border:none;";
        $rTdStyle = "font-size:11px; color:#4b5563; padding:8px 10px; border:none;";
        ?>
        <table width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr style="background:#fef2f2; border-bottom:2px solid #fecaca;">
                    <th style="<?= $rThStyle ?>">#</th>
                    <th style="<?= $rThStyle ?>"><?php echo lang('website.product'); ?></th>
                    <th style="<?= $rThStyle ?>">MRP</th>
                    <th style="<?= $rThStyle ?>"><?php echo lang('website.discount'); ?></th>
                    <th style="<?= $rThStyle ?>"><?php echo lang('website.quantity'); ?></th>
                    <th style="<?= $rThStyle ?>">Taxable Amt</th>
                    <?php foreach ($allUniqueTaxes as $tKey => $tName): ?>
                        <th style="<?= $rThStyle ?>"><?= esc($tName); ?> (%)</th>
                        <th style="<?= $rThStyle ?>"><?= esc($tName); ?> (Amt)</th>
                    <?php endforeach; ?>
                    <th style="<?= $rThStyle ?> text-align:right;"><?php echo lang('website.subtotal'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($returnedProducts as $index => $product):
                    $rMrp = (float)$product['price'];
                    $rSellingPrice = ($product['discounted_price'] > 0) ? (float)$product['discounted_price'] : $rMrp;
                    $rDiscountTotal = ($rMrp - $rSellingPrice) * $product['quantity'];
                    $rLineTotal = $rSellingPrice * $product['quantity'];

                    $rProductTaxBreakdown = isset($taxBreakdowns[$product['order_product_id']]) ? $taxBreakdowns[$product['order_product_id']] : [];
                    $rTotalBreakdownTax = 0;
                    $rTaxByKey = [];
                    foreach ($rProductTaxBreakdown as $tb) {
                        $rTotalBreakdownTax += (float)$tb['tax_amount'];
                        $rTaxByKey[$tb['tax_name']] = [
                            'percentage' => $tb['tax_percentage'],
                            'amount'     => (float)$tb['tax_amount'],
                        ];
                    }

                    $rIsInclusive = ((float)$product['tax_amount'] == 0 && (float)$product['tax_percentage'] > 0);
                    if ($rIsInclusive && $rTotalBreakdownTax == 0 && !empty($rProductTaxBreakdown)) {
                        $rTotalPct = array_sum(array_column($rProductTaxBreakdown, 'tax_percentage'));
                        if ($rTotalPct > 0) {
                            $rBaseForTax = $rLineTotal / (1 + $rTotalPct / 100);
                            foreach ($rProductTaxBreakdown as $tb) {
                                $rAmt = round($rBaseForTax * (float)$tb['tax_percentage'] / 100, 2);
                                $rTotalBreakdownTax += $rAmt;
                                $rTaxByKey[$tb['tax_name']] = [
                                    'percentage' => $tb['tax_percentage'],
                                    'amount'     => $rAmt,
                                ];
                            }
                        }
                    }
                    $rTaxableAmt = $rIsInclusive ? ($rLineTotal - $rTotalBreakdownTax) : $rLineTotal;
                    $rRowTotal = $rLineTotal + (float)$product['tax_amount'];
                ?>
                <tr style="border-bottom:1px solid #fee2e2;">
                    <td style="<?= $rTdStyle ?> color:#9ca3af;"><?= $index + 1 ?></td>
                    <td style="padding:8px 10px; border:none;">
                        <div style="font-size:12px; font-weight:600; color:#1a1a2e;"><?= esc($product['product_name']) ?></div>
                        <div style="font-size:10px; color:#7b8499; margin-top:2px;"><?= esc($product['product_variant_name']) ?></div>
                    </td>
                    <td style="<?= $rTdStyle ?>"><?= number_format($rMrp, 2) ?></td>
                    <td style="<?= $rTdStyle ?>"><?= number_format($rDiscountTotal, 2) ?></td>
                    <td style="<?= $rTdStyle ?>"><?= $product['quantity'] ?></td>
                    <td style="<?= $rTdStyle ?>"><?= number_format($rTaxableAmt, 2) ?></td>
                    <?php foreach ($allUniqueTaxes as $tKey => $tName): ?>
                        <td style="<?= $rTdStyle ?>"><?= isset($rTaxByKey[$tName]) ? number_format($rTaxByKey[$tName]['percentage'], 2) . '%' : '-'; ?></td>
                        <td style="<?= $rTdStyle ?>"><?= isset($rTaxByKey[$tName]) ? number_format($rTaxByKey[$tName]['amount'], 2) : '0.00'; ?></td>
                    <?php endforeach; ?>
                    <td style="font-size:12px; font-weight:600; color:#1a1a2e; padding:8px 10px; border:none; text-align:right;"><?= number_format($rRowTotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- ══ CHARGES & FEES ══ -->
    <?php
    $hasCharges = !empty($orderDetails['delivery_charge']) || !empty($orderDetails['additional_charge']) || !empty($orderDetails['delivery_tip_amount']);
    $chargeTaxBreakdowns = $chargeTaxBreakdowns ?? [];
    // Collect unique charge tax names for dynamic columns
    $allChargeTaxNames = [];
    foreach ($chargeTaxBreakdowns as $chargeType => $taxes) {
        foreach ($taxes as $t) {
            if (!isset($allChargeTaxNames[$t['tax_name']])) {
                $allChargeTaxNames[$t['tax_name']] = $t['tax_name'];
            }
        }
    }
    ?>
    <?php if ($hasCharges): ?>
    <div style="border:1px solid #e8eaf0; border-top:none; padding:0; margin-top:2px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5; border-bottom:1px solid #e0e0e0;">
                    <th colspan="<?= 2 + count($allChargeTaxNames) * 2 ?>" style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#374151; padding:9px 12px; text-align:left; border:none;">Charges &amp; Fees</th>
                </tr>
                <tr style="background:#fafafa; border-bottom:1px solid #e0e0e0;">
                    <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:left; border:none;">Description</th>
                    <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:right; border:none;">Amount</th>
                    <?php foreach ($allChargeTaxNames as $txName): ?>
                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:right; border:none;"><?= esc($txName) ?>&nbsp;(%)</th>
                        <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:right; border:none;"><?= esc($txName) ?>&nbsp;(Amt)</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderDetails['delivery_charge']) && $orderDetails['delivery_charge'] > 0): ?>
                <?php $dcTaxes = $chargeTaxBreakdowns['delivery'] ?? []; $dcByName = array_column($dcTaxes, null, 'tax_name'); ?>
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="font-size:11px; color:#374151; padding:8px 12px;">Delivery Charge</td>
                    <td style="font-size:11px; color:#374151; padding:8px 12px; text-align:right;"><?= inv_fmt($sym, $symLeft, $orderDetails['delivery_charge']) ?></td>
                    <?php foreach ($allChargeTaxNames as $txName): ?>
                        <td style="font-size:11px; color:#6b7280; padding:8px 12px; text-align:right;"><?= isset($dcByName[$txName]) ? number_format($dcByName[$txName]['tax_percentage'], 2) . '%' : '-' ?></td>
                        <td style="font-size:11px; color:#374151; padding:8px 12px; text-align:right;"><?= isset($dcByName[$txName]) ? number_format($dcByName[$txName]['tax_amount'], 2) : '0.00' ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endif; ?>
                <?php if (!empty($orderDetails['additional_charge']) && $orderDetails['additional_charge'] > 0): ?>
                <?php $acTaxes = $chargeTaxBreakdowns['additional'] ?? []; $acByName = array_column($acTaxes, null, 'tax_name'); ?>
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="font-size:11px; color:#374151; padding:8px 12px;"><?= esc($settings['additional_charge_name'] ?? 'Additional Charge') ?></td>
                    <td style="font-size:11px; color:#374151; padding:8px 12px; text-align:right;"><?= inv_fmt($sym, $symLeft, $orderDetails['additional_charge']) ?></td>
                    <?php foreach ($allChargeTaxNames as $txName): ?>
                        <td style="font-size:11px; color:#6b7280; padding:8px 12px; text-align:right;"><?= isset($acByName[$txName]) ? number_format($acByName[$txName]['tax_percentage'], 2) . '%' : '-' ?></td>
                        <td style="font-size:11px; color:#374151; padding:8px 12px; text-align:right;"><?= isset($acByName[$txName]) ? number_format($acByName[$txName]['tax_amount'], 2) : '0.00' ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endif; ?>
                <?php if ($invTip > 0): ?>
                <?php $tipTaxes = $chargeTaxBreakdowns['tip'] ?? []; $tipByName = array_column($tipTaxes, null, 'tax_name'); ?>
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="font-size:11px; color:#374151; padding:8px 12px;">Delivery Tip</td>
                    <td style="font-size:11px; color:#374151; padding:8px 12px; text-align:right;"><?= inv_fmt($sym, $symLeft, $invTip) ?></td>
                    <?php foreach ($allChargeTaxNames as $txName): ?>
                        <td style="font-size:11px; color:#6b7280; padding:8px 12px; text-align:right;"><?= isset($tipByName[$txName]) ? number_format($tipByName[$txName]['tax_percentage'], 2) . '%' : '-' ?></td>
                        <td style="font-size:11px; color:#374151; padding:8px 12px; text-align:right;"><?= isset($tipByName[$txName]) ? number_format($tipByName[$txName]['tax_amount'], 2) : '0.00' ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- ══ TAX SUMMARY ══ -->
    <?php
    $combinedTaxSummaryWeb = [];
    foreach ($taxSummary as $ts) {
        $k = $ts['name'] . '_' . $ts['percentage'];
        if (!isset($combinedTaxSummaryWeb[$k])) {
            $combinedTaxSummaryWeb[$k] = ['name' => $ts['name'], 'percentage' => $ts['percentage'], 'amount' => 0];
        }
        $combinedTaxSummaryWeb[$k]['amount'] += $ts['amount'];
    }
    foreach ($chargeTaxBreakdowns as $ctTaxesWeb) {
        foreach ($ctTaxesWeb as $ct) {
            $k = $ct['tax_name'] . '_' . $ct['tax_percentage'];
            if (!isset($combinedTaxSummaryWeb[$k])) {
                $combinedTaxSummaryWeb[$k] = ['name' => $ct['tax_name'], 'percentage' => $ct['tax_percentage'], 'amount' => 0];
            }
            $combinedTaxSummaryWeb[$k]['amount'] += (float)$ct['tax_amount'];
        }
    }
    ?>
    <?php if (!empty($combinedTaxSummaryWeb)): ?>
    <div style="border:1px solid #e8eaf0; border-top:none; margin-top:2px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5; border-bottom:1px solid #e0e0e0;">
                    <th colspan="3" style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#374151; padding:9px 12px; text-align:left; border:none;">Tax Summary</th>
                </tr>
                <tr style="background:#fafafa; border-bottom:1px solid #e0e0e0;">
                    <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:left; border:none;">Tax Name</th>
                    <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:center; border:none;">Rate</th>
                    <th style="font-size:10px; font-weight:600; color:#6b7280; padding:7px 12px; text-align:right; border:none;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $grandTaxTotalWeb = 0; ?>
                <?php foreach ($combinedTaxSummaryWeb as $cts): ?>
                <?php $grandTaxTotalWeb += $cts['amount']; ?>
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="font-size:11px; color:#374151; padding:7px 12px;"><?= esc($cts['name']) ?></td>
                    <td style="font-size:11px; color:#6b7280; padding:7px 12px; text-align:center;"><?= number_format($cts['percentage'], 2) ?>%</td>
                    <td style="font-size:11px; color:#374151; padding:7px 12px; text-align:right;"><?= inv_fmt($sym, $symLeft, $cts['amount']) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr style="background:#f5f5f5; border-top:1px solid #e0e0e0;">
                    <td colspan="2" style="font-size:11px; font-weight:700; color:#1a1a2e; padding:7px 12px;">Total Tax</td>
                    <td style="font-size:11px; font-weight:700; color:#1a1a2e; padding:7px 12px; text-align:right;"><?= inv_fmt($sym, $symLeft, $grandTaxTotalWeb) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- ══ BILL FOOTER ══ -->
    <div style="text-align:center; padding:14px 20px; background:#f8f9fc; border:1px solid #e8eaf0; border-top:1px dashed #d1d5db; border-radius:0 0 12px 12px; font-size:11px; color:#7b8499; margin-top:2px;">
        <?php echo lang('website.bill_generated_by'); ?> &nbsp;<strong style="color:#1a1a2e;"><?= esc($settings['business_name']) ?></strong>
    </div>

</div>
