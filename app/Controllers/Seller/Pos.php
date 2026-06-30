<?php

namespace App\Controllers\Seller;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\ProductModel;
use App\Models\ProductVariantsModel;
use App\Models\UserModel;
use App\Models\SellerModel;
use App\Models\OrderAdditionalChargeModel;
use App\Models\PosCartSessionModel;
use App\Models\SettingsModel;
use App\Models\CountryModel;
use App\Models\PosPaymentMethodModel;

class Pos extends BaseController
{
    protected $orderModel;
    protected $orderProductModel;
    protected $productModel;
    protected $productVariantsModel;
    protected $userModel;
    protected $sellerModel;
    protected $orderAdditionalChargeModel;
    protected $posCartSessionModel;
    protected $posPaymentMethodModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderProductModel = new OrderProductModel();
        $this->productModel = new ProductModel();
        $this->productVariantsModel = new ProductVariantsModel();
        $this->userModel = new UserModel();
        $this->sellerModel = new SellerModel();
        $this->orderAdditionalChargeModel = new OrderAdditionalChargeModel();
        $this->posCartSessionModel = new PosCartSessionModel();
        $this->posPaymentMethodModel = new PosPaymentMethodModel();
    }

    public function index()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Seller') {

            
            $settingModel = new SettingsModel();
            $countryModel = new CountryModel();
            $data = [
                'settings' => $settingModel->getSettings(),
                'country' => $countryModel->where('is_active', 1)->first(),
                'pos_payment_methods' => $this->posPaymentMethodModel->findAll()
            ];
    
            return view('sellerPanel/pos/posOrder', $data);
        } else {
            return redirect()->to('seller/auth/login');
        }
    }

    public function getTopProducts()
    {
        
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        


        $products = $this->productModel
            ->select('product.*, product_variants.id as variant_id, product_variants.title as variant_title, 
                      product_variants.price, product_variants.discounted_price, product_variants.stock,
                      product_variants.is_unlimited_stock')
            ->join('product_variants', 'product_variants.product_id = product.id')
            ->where('product.seller_id', session()->get('user_id'))
            ->where('product.status', 1)
            ->where('product.is_delete', 0)
            ->where('product_variants.status', 1)
            ->where('product_variants.is_delete', 0)
            ->orderBy('product.popular', 'DESC')
            ->limit(20)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'products' => $products
        ]);
    }

    public function searchProducts()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        
        $keyword = $this->request->getGet('keyword');

        if ( !$keyword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'keyword are required'
            ]);
        }

        $products = $this->productModel
            ->select('product.*, product_variants.id as variant_id, product_variants.title as variant_title, 
                      product_variants.price, product_variants.discounted_price, product_variants.stock,
                      product_variants.is_unlimited_stock')
            ->join('product_variants', 'product_variants.product_id = product.id')
            ->where('product.seller_id', session()->get('user_id'))
            ->where('product.status', 1)
            ->where('product.is_delete', 0)
            ->where('product_variants.status', 1)
            ->where('product_variants.is_delete', 0)
            ->groupStart()
                ->like('product.product_name', $keyword)
                ->orLike('product_variants.title', $keyword)
            ->groupEnd()
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'products' => $products
        ]);
    }

    public function getProductDetails()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        $productId = $this->request->getGet('product_id');
        $variantId = $this->request->getGet('variant_id');

        if (!$productId || !$variantId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product ID and Variant ID are required'
            ]);
        }

        $product = $this->productModel
            ->select('product.*, product_variants.id as variant_id, product_variants.title as variant_title, 
                      product_variants.price, product_variants.discounted_price, product_variants.stock,
                      product_variants.is_unlimited_stock')
            ->join('product_variants', 'product_variants.product_id = product.id')
            ->where('product.id', $productId)
            ->where('product_variants.id', $variantId)
            ->first();

        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        // Get tax percentage from tax table if needed
        if ($product['tax_id'] > 0) {
            $taxModel = new \App\Models\TaxModel();
            $tax = $taxModel->find($product['tax_id']);
            $product['tax_percentage'] = $tax['percentage'] ?? 0;
        } else {
            $product['tax_percentage'] = 0;
        }

        return $this->response->setJSON([
            'success' => true,
            'product' => $product
        ]);
    }

    public function searchCustomer()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        
        $keyword = $this->request->getGet('keyword');

        if (!$keyword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keyword is required'
            ]);
        }

        $customers = $this->userModel
            ->select('id, name, mobile, email')
            ->groupStart()
                ->like('name', $keyword)
                ->orLike('mobile', $keyword)
                ->orLike('email', $keyword)
            ->groupEnd()
            ->limit(10)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'customers' => $customers
        ]);
    }

    public function saveCartSession()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        
        $postData = $this->request->getJSON(true);
        
        $data = [
            'session_id' => $postData['session_id'],
            'seller_id' => session()->get('user_id'),
            'user_id' => $postData['user_id'] ?? null,
            'customer_name' => $postData['customer_name'] ?? null,
            'customer_mobile' => $postData['customer_mobile'] ?? null,
            'cart_data' => json_encode($postData['cart_items']),
            'additional_discount' => $postData['additional_discount'] ?? 0,
            'additional_discount_type' => $postData['additional_discount_type'] ?? null,
            'additional_charges' => json_encode($postData['additional_charges'] ?? []),
            'created_by_admin' =>  0  
        ];

        $existing = $this->posCartSessionModel->where('session_id', $data['session_id'])->first();
        
        if ($existing) {
            $this->posCartSessionModel->update($existing['id'], $data);
        } else {
            $this->posCartSessionModel->insert($data);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Cart session saved successfully'
        ]);
    }

    public function getCartSessions()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        
        
        $sessions = $this->posCartSessionModel
            ->where('seller_id', session()->get('user_id'))
            ->where('created_by_admin', 0)
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        foreach ($sessions as &$session) {
            $session['cart_data'] = json_decode($session['cart_data'], true);
            $session['additional_charges'] = json_decode($session['additional_charges'], true);
        }

        return $this->response->setJSON([
            'success' => true,
            'sessions' => $sessions
        ]);
    }

    public function getCartSession()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        
        $sessionId = $this->request->getGet('session_id');
        
        $session = $this->posCartSessionModel->where('created_by_admin', session()->get('user_id'))->where('session_id', $sessionId)->first();

        if ($session) {
            $session['cart_data'] = json_decode($session['cart_data'], true);
            $session['additional_charges'] = json_decode($session['additional_charges'], true);
        }

        return $this->response->setJSON([
            'success' => true,
            'session' => $session
        ]);
    }

    public function printInvoice()
    {
        
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        date_default_timezone_set($this->timeZone['timezone']); // Set the timezone
        
        $orderId = $this->request->getGet('order_id');
        
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        // Get order details
        $order = $this->orderModel->select('orders.*, pos_payment_method.name as pos_payment_method_name')
                ->where('orders.id', $orderId)
                ->where('orders.is_pos_order', 1)
                ->join('pos_payment_method', 'pos_payment_method.id = orders.pos_payment_method_id', 'left')
                ->first();
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'POS Order not found'
            ]);
        }

        // Get order products
        $orderProducts = $this->orderProductModel
            ->where('order_id', $orderId)
            ->findAll();

        // Get additional charges
        $additionalCharges = $this->orderAdditionalChargeModel
            ->where('order_id', $orderId)
            ->findAll();

        // Get seller details
        $seller = $this->sellerModel->find($orderProducts[0]['seller_id'] ?? 0);

        // Get customer details if exists
        $customer = null;
        if ($order['user_id'] > 0) {
            $customer = $this->userModel->find($order['user_id']);
        }
        $settingModel = new SettingsModel();
        $countryModel = new CountryModel();

        $data = [
            'order' => $order,
            'orderProducts' => $orderProducts,
            'additionalCharges' => $additionalCharges,
            'seller' => $seller,
            'customer' => $customer,
            'settings' => $settingModel->getSettings(),
            'country' => $countryModel->where('is_active', 1)->first()
        ];

        return view('pos/thermalPrint', $data);
    }

    public function deleteCartSession()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        
        $sessionId = $this->request->getPost('session_id');
        
        $this->posCartSessionModel->where('created_by_admin', 0)->where('seller_id', session()->get('user_id'))->where('session_id', $sessionId)->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Cart session deleted successfully'
        ]);
    }

    public function placeOrder()
    {
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login'); // Redirect to login if session is not set
        }
        date_default_timezone_set($this->timeZone['timezone']); // Set the timezone
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $postData = $this->request->getJSON(true);
            
            // Generate order ID
            $orderId = 'POS' . time() . rand(1000, 9999);
            
            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            
            foreach ($postData['cart_items'] as $item) {
                $itemTotal = $item['final_price'] * $item['quantity'];
                $subtotal += $itemTotal;
                $totalTax += $item['tax_amount'] * $item['quantity'];
            }

            // Calculate additional discount
            $additionalDiscount = 0;
            if (!empty($postData['additional_discount'])) {
                if ($postData['additional_discount_type'] === 'percentage') {
                    $additionalDiscount = ($subtotal * $postData['additional_discount']) / 100;
                } else {
                    $additionalDiscount = min($postData['additional_discount'], $subtotal);
                }
            }

            // Calculate additional charges total
            $additionalChargesTotal = 0;
            if (!empty($postData['additional_charges'])) {
                foreach ($postData['additional_charges'] as $charge) {
                    $additionalChargesTotal += $charge['amount'];
                }
            }

            // Final total
            $finalTotal = $subtotal + $totalTax - $additionalDiscount + $additionalChargesTotal;

            // Insert order
            $orderData = [
                'order_id' => $orderId,
                'user_id' => $postData['user_id'] ?? 0,
                'address_id' => 0,
                'payment_method_id' =>  0,
                'pos_payment_method_id' => $postData['payment_method_id'] ?? 1,
                'coupon_id' => 0,
                'status' => 1,
                'order_date' => date('Y-m-d H:i:s'),
                'subtotal' => $subtotal,
                'tax' => $totalTax,
                'delivery_charge' => 0,
                'additional_charge' => $additionalChargesTotal,
                'coupon_amount' => 0,
                'additional_discount' => $additionalDiscount,
                'additional_discount_type' => $postData['additional_discount_type'] ?? null,
                'is_pos_order' => 1,
                'pos_by' => 2,
                'pos_created_by' => session()->get('user_id'),
                'customer_name' => $postData['customer_name'] ?? null,
                'customer_mobile' => $postData['customer_mobile'] ?? null,
                'delivery_method' => 'selfPickup',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $insertedOrderId = $this->orderModel->insert($orderData);

            // Insert order products
            foreach ($postData['cart_items'] as $item) {
                $orderProductData = [
                    'user_id' => $postData['user_id'] ?? 0,
                    'seller_id' => session()->get('user_id'),
                    'order_id' => $insertedOrderId,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'product_name' => $item['product_name'],
                    'product_variant_name' => $item['variant_title'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discounted_price' => $item['final_price'],
                    'tax_amount' => $item['tax_amount'],
                    'tax_percentage' => $item['tax_percentage'],
                    'discount' => $item['price'] - $item['final_price']
                ];

                $this->orderProductModel->insert($orderProductData);

                // Update stock if not unlimited
                $variant = $this->productVariantsModel->find($item['variant_id']);
                if ($variant && $variant['is_unlimited_stock'] == 0) {
                    $newStock = max(0, $variant['stock'] - $item['quantity']);
                    $this->productVariantsModel->update($item['variant_id'], ['stock' => $newStock]);
                }
            }

            // Insert additional charges
            if (!empty($postData['additional_charges'])) {
                foreach ($postData['additional_charges'] as $charge) {
                    $this->orderAdditionalChargeModel->insert([
                        'order_id' => $insertedOrderId,
                        'charge_name' => $charge['name'],
                        'charge_amount' => $charge['amount']
                    ]);
                }
            }

            // Delete cart session if provided
            if (!empty($postData['session_id'])) {
                $this->posCartSessionModel->where('session_id', $postData['session_id'])->delete();
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to place order'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order placed successfully',
                'order_id' => $orderId,
                'db_order_id' => $insertedOrderId
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function reportIndex()
    {
        $session = session();
        if ($session->has('user_id') && session('account_type') == 'Seller') {

            
            $settingModel = new SettingsModel();
            $countryModel = new CountryModel();
            $data = [
                'settings' => $settingModel->getSettings(),
                'country' => $countryModel->where('is_active', 1)->first(),
                'pos_payment_methods' => $this->posPaymentMethodModel->findAll()
            ];
    
            return view('sellerPanel/report/posReport', $data);
        } else {
            return redirect()->to('seller/auth/login');
        }
    }
    
    public function reportList()
    {
        // Ensure session is started
        if (!session()->has('user_id') || session('account_type') != 'Seller') {
            return redirect()->to('seller/login');
        }
    
        $request = $this->request->getPost();
    
        // Get filters from POST
        $orderDate = $request['order_date'] ?? '';
        $paymentMethodId = $request['payment_methods'] ?? '';
    
        // Build query
        $builder = $this->orderModel
            ->select('orders.*, order_products.seller_id, pos_payment_method.name as payment_method_name')
            ->join('order_products', 'order_products.order_id = orders.id', 'left')
            ->join('pos_payment_method', 'pos_payment_method.id = orders.pos_payment_method_id', 'left')
            ->where('orders.is_pos_order', 1)
            ->where('order_products.seller_id', session()->get('user_id'))
            ->groupBy('orders.id');
    
        // Apply date filter
        if (!empty($orderDate)) {
            $dates = explode(' - ', $orderDate);
            if (count($dates) == 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dates[1]));
                $builder->where('orders.order_date >=', $startDate)
                        ->where('orders.order_date <=', $endDate);
            }
        }
    
        $builder->where('order_products.seller_id', session()->get('user_id'));
        
    
        // Apply payment method filter
        if (!empty($paymentMethodId)) {
            $builder->where('orders.pos_payment_method_id', $paymentMethodId);
        }
    
        // Get all orders
        $orders = $builder->orderBy('orders.order_date', 'DESC')->findAll();
    
        // Format data
        $output = ['data' => []];
        $countryModel = new CountryModel();
        $country = $countryModel->where('is_active', 1)->first();
        foreach ($orders as $order) {
            // Get customer details
            $customerName = $order['customer_name'] ?? 'Walk-in Customer';
            $customerMobile = $order['customer_mobile'] ?? 'N/A';
    
            if ($order['user_id'] > 0) {
                $customer = $this->userModel->find($order['user_id']);
                if ($customer) {
                    $customerName = $customer['name'];
                    $customerMobile = $customer['mobile'];
                }
            }
            
            if ($order['pos_by'] == 1) {
                $generatedBy = 'Admin ';
            }elseif($order['pos_by'] == 2){
                $generatedBy = 'Seller ';
            }
            
    
            // Calculate final amount
            $finalAmount = $order['subtotal'] + $order['tax'] - $order['additional_discount'] + $order['additional_charge'];
    
            // Get seller name
            $sellerName = 'N/A';
            if (!empty($order['seller_id'])) {
                $seller = $this->sellerModel->find($order['seller_id']);
                $sellerName = $seller['store_name'] ?? $seller['name'] ?? 'N/A';
            }
    
            // Order ID column
            $orderIdHtml = '<strong>' . esc($order['order_id']) . '</strong><br><small class="text-muted">Seller: ' . esc($sellerName) . '</small>';
    
            // Date column
            $dateHtml = date('d M Y', strtotime($order['order_date'])) . '<br><small class="text-muted">' . date('h:i A', strtotime($order['order_date'])) . '</small>';
    
            // Payment method badge color mapping
            $badgeClasses = [
                1 => 'primary',    // Cash
                2 => 'warning', // Card
                3 => 'success', // UPI
                4 => 'danger'   // Net Banking
            ];
    
            $paymentClass = $badgeClasses[$order['pos_payment_method_id']] ?? 'secondary';
            $paymentName = $order['payment_method_name'] ?? 'N/A';
            $paymentBadge = '<span class="badge badge-' . $paymentClass . '">' . esc($paymentName) . '</span>';
    
            // Amount column
            $amountHtml = '<strong>'. $country['currency_symbol'] . number_format($finalAmount, 2) . '</strong><br><small class="text-muted">Subtotal: ' . $country['currency_symbol'] . number_format($order['subtotal'], 2) . '</small>';
    
            // Action button (Flat Icon)
            $action = '<a href="' . base_url('seller/pos/printInvoice?order_id=' . $order['id']) . '" target="_blank" class="btn btn-primary-light btn-xs" data-tooltip="tooltip" title="Print Invoice"><i class="fi fi-tr-print"></i></a>';
    
            $output['data'][] = [
                $orderIdHtml,
                esc($customerName),
                esc($customerMobile),
                $dateHtml,
                $paymentBadge,
                $amountHtml,
                $generatedBy,
                $action
            ];
        }
    
        return $this->response->setJSON($output);
    }

    
    
}