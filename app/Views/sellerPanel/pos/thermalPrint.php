<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= $order['order_id'] ?></title>
    <!-- Add this in the <head> section of thermal print page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        @media print {
            body {
                width: 80mm;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .header p {
            font-size: 10px;
            margin: 2px 0;
        }
        
        .order-info {
            margin: 10px 0;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        
        .order-info p {
            margin: 3px 0;
            font-size: 10px;
        }
        
        .customer-info {
            margin: 8px 0;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        
        .customer-info p {
            margin: 3px 0;
            font-size: 10px;
        }
        
        .items-table {
            width: 100%;
            margin: 10px 0;
            border-bottom: 1px dashed #000;
        }
        
        .items-table thead {
            border-bottom: 1px solid #000;
        }
        
        .items-table th {
            text-align: left;
            padding: 5px 2px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 4px 2px;
            font-size: 10px;
        }
        
        .items-table .right {
            text-align: right;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .item-variant {
            font-size: 9px;
            color: #333;
        }
        
        .totals {
            margin: 10px 0;
            border-bottom: 2px dashed #000;
            padding-bottom: 8px;
        }
        
        .totals .row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
            font-size: 11px;
        }
        
        .totals .row.grand-total {
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
            padding-top: 5px;
            border-top: 1px solid #000;
        }
        
        .payment-info {
            margin: 10px 0;
            text-align: center;
            font-size: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }
        
        .footer p {
            margin: 3px 0;
        }
        
        .thank-you {
            font-weight: bold;
            font-size: 12px;
            margin: 10px 0;
        }
        
        .barcode {
            text-align: center;
            margin: 10px 0;
            font-family: 'Libre Barcode 128', cursive;
            font-size: 40px;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print Invoice</button>
    
    <!-- Header -->
    <div class="header">
        <h1><?= $seller['name'] ?? 'Store Name' ?></h1>
        <?php if (!empty($seller['mobile'])): ?>
        <p>Tel: <?= $seller['mobile'] ?></p>
        <?php endif; ?>
        <?php if (!empty($seller['email'])): ?>
        <p>Email: <?= $seller['email'] ?></p>
        <?php endif; ?>
        <p>GST: <?= $seller['gst_number'] ?? 'N/A' ?></p>
    </div>
    
    <!-- Order Info -->
    <div class="order-info">
        <p><strong>INVOICE</strong></p>
        <p>Order ID: <strong><?= $order['order_id'] ?></strong></p>
        <p>Date: <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
        <?php if ($order['is_pos_order']): ?>
        <p>Type: <strong>POS Order</strong></p>
        <?php endif; ?>
    </div>
    
    <!-- Customer Info -->
    <?php if ($customer || $order['customer_name']): ?>
    <div class="customer-info">
        <p><strong>CUSTOMER DETAILS</strong></p>
        <p>Name: <?= $customer['name'] ?? $order['customer_name'] ?></p>
        <p>Mobile: <?= $customer['mobile'] ?? $order['customer_mobile'] ?></p>
        <?php if (!empty($customer['email'])): ?>
        <p>Email: <?= $customer['email'] ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Item</th>
                <th style="width: 15%;" class="right">Qty</th>
                <th style="width: 17%;" class="right">Rate</th>
                <th style="width: 18%;" class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderProducts as $item): ?>
            <tr>
                <td>
                    <div class="item-name"><?= $item['product_name'] ?></div>
                    <div class="item-variant"><?= $item['product_variant_name'] ?></div>
                </td>
                <td class="right"><?= $item['quantity'] ?></td>
                <td class="right"><?= $country['currency_symbol'] ?><?= number_format($item['discounted_price'], 2) ?></td>
                <td class="right"><?= $country['currency_symbol'] ?><?= number_format($item['discounted_price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Totals -->
    <div class="totals">
        <div class="row">
            <span>Subtotal:</span>
            <span><?= $country['currency_symbol'] ?><?= number_format($order['subtotal'], 2) ?></span>
        </div>
        
        <?php if ($order['tax'] > 0): ?>
        <div class="row">
            <span>Tax (GST):</span>
            <span><?= $country['currency_symbol'] ?><?= number_format($order['tax'], 2) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($order['additional_discount'] > 0): ?>
        <div class="row">
            <span>Discount<?= $order['additional_discount_type'] == 'percentage' ? ' (%)' : '' ?>:</span>
            <span>- <?= $country['currency_symbol'] ?><?= number_format($order['additional_discount'], 2) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($additionalCharges)): ?>
            <?php foreach ($additionalCharges as $charge): ?>
            <div class="row">
                <span><?= $charge['charge_name'] ?>:</span>
                <span><?= $country['currency_symbol'] ?><?= number_format($charge['charge_amount'], 2) ?></span>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="row grand-total">
            <span>TOTAL:</span>
            <span><?= $country['currency_symbol'] ?><?= number_format(
                $order['subtotal'] + 
                $order['tax'] - 
                $order['additional_discount'] + 
                $order['additional_charge'], 
                2
            ) ?></span>
        </div>
    </div>
    
    <!-- Payment Info -->
    <div class="payment-info">
        <p><strong>Payment Method:</strong> 
        <?php 
            echo $order['pos_payment_method_name'] ?? 'N/A';
            ?>
        </p>
        <p><strong>Status:</strong> Paid</p>
    </div>
    
    <!-- Barcode -->
    <div class="barcode" style="text-align: center; margin: 10px 0;">
        <svg id="barcode"></svg>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p class="thank-you">*** THANK YOU! VISIT AGAIN ***</p>
        <p>Powered by <?= $settings['business_name'] ?></p>
        <p><?= date('d M Y, h:i A') ?></p>
    </div>
    
    <script>
        JsBarcode("#barcode", "<?= $order['order_id'] ?>", {
        format: "CODE128",
        width: 1.5,
        height: 60,
        displayValue: true,
        fontSize: 14,
        margin: 5
    });
    
        // Auto print on load
        window.onload = function() {
            // Uncomment below line to auto-print
            // window.print();
        };
        
        // Close window after printing
        window.onafterprint = function() {
            // window.close();
        };
    </script>
</body>
</html>