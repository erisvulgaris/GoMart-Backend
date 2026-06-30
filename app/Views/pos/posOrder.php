<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/daterangepicker/daterangepicker.css') ?>">
    <style>
        .pos-container { height: calc(100vh - 100px); overflow: hidden; }
        .product-grid { height: calc(100vh - 250px); overflow-y: auto; }
        .cart-section { height: calc(100vh - 100px); overflow-y: auto; background: #f8f9fa; }
        .product-card { cursor: pointer; transition: all 0.3s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .cart-item { border-bottom: 1px solid #dee2e6; padding: 10px 0; }
        .cart-item:last-child { border-bottom: none; }
        .quantity-control { display: flex; align-items: center; gap: 5px; }
        .quantity-control button { width: 30px; height: 30px; padding: 0; }
        .additional-charge-item { background: #e9ecef; padding: 8px; margin-bottom: 5px; border-radius: 4px; }
        .search-results { position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; 
                          max-height: 300px; overflow-y: auto; width: 100%; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .search-result-item { padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; }
        .search-result-item:hover { background: #f8f9fa; }
        .search-result-item.selected { background: #007bff; color: white; }
        .customer-badge { display: inline-block; background: #28a745; color: white; padding: 5px 10px; 
                         border-radius: 20px; font-size: 12px; margin-right: 5px; }
        .hold-tabs { margin-bottom: 10px; display: flex; flex-wrap: wrap; gap: 5px; }
        .hold-tab { display: inline-flex; align-items: center; padding: 8px 15px; background: #6c757d; 
                    color: white; border-radius: 5px 5px 0 0; cursor: pointer; position: relative; }
        .hold-tab.active { background: #007bff; }
        .hold-tab .close-tab { margin-left: 10px; color: white; opacity: 0.7; cursor: pointer; }
        .hold-tab .close-tab:hover { opacity: 1; }
        .keyboard-shortcuts-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }
        .shortcuts-modal .shortcut-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .shortcuts-modal .shortcut-key {
            background: #343a40;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
        .cart-item.keyboard-selected {
            background: #e7f3ff;
            border-left: 3px solid #007bff;
        }
    </style>
</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm layout-fixed <?php echo $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">
    <div class="wrapper">
        <?= $this->include('template/header') ?>
        <?= $this->include('template/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">POS System</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active">POS</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Products Section -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                    <h3 class="card-title mb-0">Products</h3>
                                
                                    <div class="card-tools d-flex align-items-center" style="gap: 10px;">
                                        <!-- Seller Dropdown -->
                                        <select id="sellerSelect" class="form-control form-control-sm" style="width: 220px;">
                                            <option value="">-- Select Seller --</option>
                                            <?php foreach ($sellers as $seller): ?>
                                                <option value="<?= $seller['id'] ?>"><?= esc($seller['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                
                                        <!-- Search Box -->
                                        <div class="input-group input-group-sm" style="width: 260px; position: relative;">
                                            <input type="text" id="productSearch" class="form-control" placeholder="Search products... (F2)">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fi fi-br-search"></i>
                                                </button>
                                            </div>
                                            <div id="searchResults" class="search-results" style="display: none; top: 100%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body product-grid">
                                    <div id="productList" class="row">
                                        <div class="col-12 text-center text-muted">
                                            <p>Please select a seller to view products</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Section -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Cart</h3>
                                    <div class="card-tools">
                                        <button class="btn btn-sm btn-info" id="holdOrderBtn">
                                            <i class="fi fi-br-pause"></i> Hold (F4)
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body cart-section p-3">
                                    <!-- Hold Tabs -->
                                    <div id="holdTabs" class="hold-tabs" style="display: none;"></div>

                                    <!-- Customer Selection -->
                                    <div class="mb-3">
                                        <label>Customer:</label>
                                        <div class="input-group" style="position: relative;">
                                            <input type="text" id="customerSearch" class="form-control" placeholder="Search or add customer... (F3)">
                                            <div class="input-group-append">
                                                <button class="btn btn-success" id="addCustomerBtn" type="button">
                                                    <i class="fi fi-br-plus"></i>
                                                </button>
                                            </div>
                                            <div id="customerSearchResults" class="search-results" style="display: none; top: 100%;"></div>
                                        </div>
                                        <div id="selectedCustomer" class="mt-2"></div>
                                    </div>

                                    <!-- Cart Items -->
                                    <div id="cartItems" class="mb-3">
                                        <p class="text-muted text-center">Cart is empty</p>
                                    </div>

                                    <!-- Additional Discount -->
                                    <div class="mb-3">
                                        <label>Additional Discount:</label>
                                        <div class="input-group">
                                            <input type="number" id="additionalDiscount" class="form-control" placeholder="0" min="0" step="0.01">
                                            <select id="discountType" class="form-control" style="max-width: 100px;">
                                                <option value="flat"><?= $country['currency_symbol'] ?> Flat</option>
                                                <option value="percentage">%</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Additional Charges -->
                                    <div class="mb-3">
                                        <label>Additional Charges:</label>
                                        <div class="input-group mb-2">
                                            <input type="text" id="chargeName" class="form-control" placeholder="Charge name">
                                            <input type="number" id="chargeAmount" class="form-control" placeholder="Amount" min="0" step="0.01">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" id="addChargeBtn" type="button">
                                                    <i class="fi fi-br-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="additionalChargesList"></div>
                                    </div>

                                    <!-- Cart Summary -->
                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span id="subtotal"><?= $country['currency_symbol'] ?>0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax:</span>
                                            <span id="totalTax"><?= $country['currency_symbol'] ?>0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Discount:</span>
                                            <span id="totalDiscount" class="text-danger">-<?= $country['currency_symbol'] ?>0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Additional Charges:</span>
                                            <span id="totalAdditionalCharges"><?= $country['currency_symbol'] ?>0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3 font-weight-bold" style="font-size: 1.2em;">
                                            <span>Total:</span>
                                            <span id="grandTotal"><?= $country['currency_symbol'] ?>0.00</span>
                                        </div>

                                        <!-- Payment Method -->
                                        <div class="mb-3">
                                            <label>Payment Method:</label>
                                            <select id="paymentMethod" class="form-control">
                                                <?php foreach($pos_payment_methods as $pos_payment_method){ ?>
                                                <option value="<?= $pos_payment_method['id'] ?>"><?= $pos_payment_method['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <button class="btn btn-success btn-block btn-lg" id="placeOrderBtn">
                                            <i class="fi fi-br-check"></i> Place Order (F9)
                                        </button>
                                        <button class="btn btn-danger btn-block" id="clearCartBtn">
                                            <i class="fi fi-br-trash-xmark"></i> Clear Cart (F8)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Add Customer Modal -->
            <div class="modal fade" id="addCustomerModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Quick Customer</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Customer Name *</label>
                                <input type="text" id="quickCustomerName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number *</label>
                                <input type="text" id="quickCustomerMobile" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="saveQuickCustomer">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keyboard Shortcuts Modal -->
            <div class="modal fade" id="shortcutsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Keyboard Shortcuts</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body shortcuts-modal">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Navigation</h6>
                                    <div class="shortcut-item">
                                        <span>Focus Product Search</span>
                                        <span class="shortcut-key">F2</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Focus Customer Search</span>
                                        <span class="shortcut-key">F3</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Navigate Search Results</span>
                                        <span class="shortcut-key">↑ ↓</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Select Item</span>
                                        <span class="shortcut-key">Enter</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Close Search</span>
                                        <span class="shortcut-key">Esc</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Cart Actions</h6>
                                    <div class="shortcut-item">
                                        <span>Navigate Cart Items</span>
                                        <span class="shortcut-key">Ctrl + ↑ ↓</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Increase Quantity</span>
                                        <span class="shortcut-key">Ctrl + →</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Decrease Quantity</span>
                                        <span class="shortcut-key">Ctrl + ←</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Remove Selected Item</span>
                                        <span class="shortcut-key">Ctrl + Del</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Hold Order</span>
                                        <span class="shortcut-key">F4</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Clear Cart</span>
                                        <span class="shortcut-key">F8</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Place Order</span>
                                        <span class="shortcut-key">F9</span>
                                    </div>
                                    <div class="shortcut-item">
                                        <span>Show Shortcuts</span>
                                        <span class="shortcut-key">F1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keyboard Shortcuts Badge -->
        <div class="keyboard-shortcuts-badge">
            <button class="btn btn-info btn-sm" id="showShortcutsBtn">
                <i class="fi fi-br-keyboard"></i> Shortcuts (F1)
            </button>
        </div>

        <?= $this->include('template/footer') ?>
    </div>

    <?= $this->include('template/script') ?>
    <script>
        const POS = {
            selectedSeller: null,
            selectedCustomer: null,
            cart: [],
            additionalCharges: [],
            holdOrders: [],
            currentTab: null,
            searchSelectedIndex: -1,
            customerSearchSelectedIndex: -1,
            cartSelectedIndex: -1,

            init() {
                this.bindEvents();
                this.bindKeyboardShortcuts();
            },

            bindEvents() {
                $('#sellerSelect').on('change', (e) => this.onSellerChange(e.target.value));
                $('#productSearch').on('input', (e) => this.searchProducts(e.target.value));
                $('#customerSearch').on('input', (e) => this.searchCustomers(e.target.value));
                $('#addCustomerBtn').on('click', () => this.showAddCustomerModal());
                $('#saveQuickCustomer').on('click', () => this.saveQuickCustomer());
                $('#addChargeBtn').on('click', () => this.addAdditionalCharge());
                $('#additionalDiscount, #discountType').on('input change', () => this.updateCartSummary());
                $('#placeOrderBtn').on('click', () => this.placeOrder());
                $('#clearCartBtn').on('click', () => this.clearCart());
                $('#holdOrderBtn').on('click', () => this.holdOrder());
                $('#showShortcutsBtn').on('click', () => this.showKeyboardShortcuts());

                $(document).on('click', (e) => {
                    if (!$(e.target).closest('#productSearch, #searchResults').length) {
                        $('#searchResults').hide();
                        this.searchSelectedIndex = -1;
                    }
                    if (!$(e.target).closest('#customerSearch, #customerSearchResults').length) {
                        $('#customerSearchResults').hide();
                        this.customerSearchSelectedIndex = -1;
                    }
                });
            },

            bindKeyboardShortcuts() {
                $(document).on('keydown', (e) => {
                    // Ignore if typing in input fields (except search fields)
                    const activeElement = document.activeElement;
                    const isTyping = ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeElement.tagName) && 
                                    !['productSearch', 'customerSearch'].includes(activeElement.id);

                    // F1 - Show keyboard shortcuts
                    if (e.key === 'F1') {
                        e.preventDefault();
                        this.showKeyboardShortcuts();
                        return;
                    }

                    // F2 - Focus product search
                    if (e.key === 'F2') {
                        e.preventDefault();
                        $('#productSearch').focus();
                        return;
                    }

                    // F3 - Focus customer search
                    if (e.key === 'F3') {
                        e.preventDefault();
                        $('#customerSearch').focus();
                        return;
                    }

                    // F4 - Hold order
                    if (e.key === 'F4') {
                        e.preventDefault();
                        this.holdOrder();
                        return;
                    }

                    // F8 - Clear cart
                    if (e.key === 'F8') {
                        e.preventDefault();
                        this.clearCart();
                        return;
                    }

                    // F9 - Place order
                    if (e.key === 'F9') {
                        e.preventDefault();
                        this.placeOrder();
                        return;
                    }

                    // Product search navigation
                    if (activeElement.id === 'productSearch' && $('#searchResults').is(':visible')) {
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            this.navigateSearchResults(1);
                            return;
                        }
                        if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            this.navigateSearchResults(-1);
                            return;
                        }
                        if (e.key === 'Enter' && this.searchSelectedIndex >= 0) {
                            e.preventDefault();
                            this.selectSearchResult();
                            return;
                        }
                        if (e.key === 'Escape') {
                            e.preventDefault();
                            $('#searchResults').hide();
                            this.searchSelectedIndex = -1;
                            return;
                        }
                    }

                    // Customer search navigation
                    if (activeElement.id === 'customerSearch' && $('#customerSearchResults').is(':visible')) {
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            this.navigateCustomerSearchResults(1);
                            return;
                        }
                        if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            this.navigateCustomerSearchResults(-1);
                            return;
                        }
                        if (e.key === 'Enter' && this.customerSearchSelectedIndex >= 0) {
                            e.preventDefault();
                            this.selectCustomerSearchResult();
                            return;
                        }
                        if (e.key === 'Escape') {
                            e.preventDefault();
                            $('#customerSearchResults').hide();
                            this.customerSearchSelectedIndex = -1;
                            return;
                        }
                    }

                    // Cart navigation (Ctrl + Arrow keys)
                    if (!isTyping && e.ctrlKey) {
                        if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            this.navigateCart(-1);
                            return;
                        }
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            this.navigateCart(1);
                            return;
                        }
                        if (e.key === 'ArrowRight' && this.cartSelectedIndex >= 0) {
                            e.preventDefault();
                            this.updateQuantity(this.cartSelectedIndex, 1);
                            return;
                        }
                        if (e.key === 'ArrowLeft' && this.cartSelectedIndex >= 0) {
                            e.preventDefault();
                            this.updateQuantity(this.cartSelectedIndex, -1);
                            return;
                        }
                        if (e.key === 'Delete' && this.cartSelectedIndex >= 0) {
                            e.preventDefault();
                            this.removeFromCart(this.cartSelectedIndex);
                            return;
                        }
                    }
                });
            },

            navigateSearchResults(direction) {
                const items = $('#searchResults .search-result-item');
                if (items.length === 0) return;

                this.searchSelectedIndex += direction;
                
                if (this.searchSelectedIndex < 0) {
                    this.searchSelectedIndex = items.length - 1;
                } else if (this.searchSelectedIndex >= items.length) {
                    this.searchSelectedIndex = 0;
                }

                items.removeClass('selected');
                const selectedItem = items.eq(this.searchSelectedIndex);
                selectedItem.addClass('selected');
                
                // Scroll into view
                const container = $('#searchResults');
                const itemOffset = selectedItem.position().top;
                const containerHeight = container.height();
                
                if (itemOffset < 0) {
                    container.scrollTop(container.scrollTop() + itemOffset);
                } else if (itemOffset + selectedItem.height() > containerHeight) {
                    container.scrollTop(container.scrollTop() + itemOffset - containerHeight + selectedItem.height());
                }
            },

            selectSearchResult() {
                const selectedItem = $('#searchResults .search-result-item').eq(this.searchSelectedIndex);
                if (selectedItem.length) {
                    selectedItem.click();
                }
            },

            navigateCustomerSearchResults(direction) {
                const items = $('#customerSearchResults .search-result-item');
                if (items.length === 0) return;

                this.customerSearchSelectedIndex += direction;
                
                if (this.customerSearchSelectedIndex < 0) {
                    this.customerSearchSelectedIndex = items.length - 1;
                } else if (this.customerSearchSelectedIndex >= items.length) {
                    this.customerSearchSelectedIndex = 0;
                }

                items.removeClass('selected');
                const selectedItem = items.eq(this.customerSearchSelectedIndex);
                selectedItem.addClass('selected');
                
                // Scroll into view
                const container = $('#customerSearchResults');
                const itemOffset = selectedItem.position().top;
                const containerHeight = container.height();
                
                if (itemOffset < 0) {
                    container.scrollTop(container.scrollTop() + itemOffset);
                } else if (itemOffset + selectedItem.height() > containerHeight) {
                    container.scrollTop(container.scrollTop() + itemOffset - containerHeight + selectedItem.height());
                }
            },

            selectCustomerSearchResult() {
                const selectedItem = $('#customerSearchResults .search-result-item').eq(this.customerSearchSelectedIndex);
                if (selectedItem.length) {
                    selectedItem.click();
                }
            },

            navigateCart(direction) {
                if (this.cart.length === 0) return;

                this.cartSelectedIndex += direction;
                
                if (this.cartSelectedIndex < 0) {
                    this.cartSelectedIndex = this.cart.length - 1;
                } else if (this.cartSelectedIndex >= this.cart.length) {
                    this.cartSelectedIndex = 0;
                }

                $('.cart-item').removeClass('keyboard-selected');
                $('.cart-item').eq(this.cartSelectedIndex).addClass('keyboard-selected');
                
                // Scroll into view
                const selectedItem = $('.cart-item').eq(this.cartSelectedIndex);
                if (selectedItem.length) {
                    selectedItem[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            },

            showKeyboardShortcuts() {
                $('#shortcutsModal').modal('show');
            },

            onSellerChange(sellerId) {
                this.selectedSeller = sellerId;
                if (sellerId) {
                    this.loadTopProducts();
                    this.loadHoldOrders();
                } else {
                    $('#productList').html('<div class="col-12 text-center text-muted"><p>Please select a seller</p></div>');
                    this.holdOrders = [];
                    this.renderHoldTabs();
                }
            },

            loadTopProducts() {
                $.get('/admin/pos/getTopProducts', { seller_id: this.selectedSeller }, (response) => {
                    if (response.success) {
                        this.renderProducts(response.products);
                    }
                });
            },

            searchProducts(keyword) {
                if (!this.selectedSeller) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Seller Selected',
                        text: 'Please select a seller first!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (keyword.length < 2) {
                    $('#searchResults').hide();
                    this.searchSelectedIndex = -1;
                    this.loadTopProducts();
                    return;
                }

                $.get('/admin/pos/searchProducts', { 
                    seller_id: this.selectedSeller, 
                    keyword: keyword 
                }, (response) => {
                    if (response.success) {
                        this.renderSearchResults(response.products);
                    }
                });
            },

            renderSearchResults(products) {
                this.searchSelectedIndex = -1;
                
                if (products.length === 0) {
                    $('#searchResults').html('<div class="p-3 text-muted">No products found</div>').show();
                    return;
                }

                let html = '';
                products.forEach(product => {
                    const finalPrice = product.discounted_price > 0 ? product.discounted_price : product.price;
                    html += `
                        <div class="search-result-item" onclick="POS.addToCart(${product.id}, ${product.variant_id})">
                            <strong>${product.product_name}</strong> - ${product.variant_title}<br>
                            <small class="text-muted"><?= $country['currency_symbol'] ?>${finalPrice}</small>
                            ${product.is_unlimited_stock == 0 ? `<small class="text-info ml-2">Stock: ${product.stock}</small>` : ''}
                        </div>
                    `;
                });
                $('#searchResults').html(html).show();
            },

            renderProducts(products) {
                if (products.length === 0) {
                    $('#productList').html('<div class="col-12 text-center text-muted"><p>No products found</p></div>');
                    return;
                }

                let html = '';
                products.forEach(product => {
                    const finalPrice = product.discounted_price > 0 ? product.discounted_price : product.price;
                    const discount = product.discounted_price > 0 ? 
                        Math.round(((product.price - product.discounted_price) / product.price) * 100) : 0;

                    html += `
                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                            <div class="card product-card h-100" onclick="POS.addToCart(${product.id}, ${product.variant_id})">
                                <img src="<?= base_url()?>${product.main_img || '<?= base_url()?>/assets/img/no-image.jpg'}" class="card-img-top" alt="${product.product_name}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1" style="font-size: 0.9em;">${product.product_name}</h6>
                                    <p class="card-text mb-1" style="font-size: 0.8em; color: #6c757d;">${product.variant_title}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-success"><?= $country['currency_symbol'] ?>${finalPrice}</strong>
                                            ${product.discounted_price > 0 ? `<br><small class="text-muted"><del><?= $country['currency_symbol'] ?>${product.price}</del></small>` : ''}
                                        </div>
                                        ${discount > 0 ? `<span class="badge badge-danger">${discount}% OFF</span>` : ''}
                                    </div>
                                    ${product.is_unlimited_stock == 0 ? `<small class="text-info">Stock: ${product.stock}</small>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#productList').html(html);
            },

            addToCart(productId, variantId) {
                if (!this.selectedSeller) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Seller Selected',
                        text: 'Please select a seller first!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                $('#searchResults').hide();
                $('#productSearch').val('');
                this.searchSelectedIndex = -1;

                $.get('/admin/pos/getProductDetails', { 
                    product_id: productId, 
                    variant_id: variantId 
                }, (response) => {
                    if (response.success) {
                        const product = response.product;
                        const existingItem = this.cart.find(item => item.variant_id === variantId);

                        if (existingItem) {
                            existingItem.quantity++;
                        } else {
                            const finalPrice = product.discounted_price > 0 ? product.discounted_price : product.price;
                            const taxPercentage = product.tax_percentage || 0;
                            const taxAmount = (finalPrice * taxPercentage) / 100;

                            this.cart.push({
                                product_id: productId,
                                variant_id: variantId,
                                product_name: product.product_name,
                                variant_title: product.variant_title,
                                price: product.price,
                                final_price: finalPrice,
                                tax_percentage: taxPercentage,
                                tax_amount: taxAmount,
                                quantity: 1,
                                stock: product.stock,
                                is_unlimited_stock: product.is_unlimited_stock
                            });
                        }

                        this.renderCart();
                        this.updateCartSummary();
                    }
                });
            },

            renderCart() {
                if (this.cart.length === 0) {
                    $('#cartItems').html('<p class="text-muted text-center">Cart is empty</p>');
                    this.cartSelectedIndex = -1;
                    return;
                }

                let html = '';
                this.cart.forEach((item, index) => {
                    const itemTotal = (item.final_price * item.quantity).toFixed(2);
                    const isSelected = this.cartSelectedIndex === index;
                    html += `
                        <div class="cart-item ${isSelected ? 'keyboard-selected' : ''}">
                            <div class="d-flex justify-content-between mb-2">
                                <div style="flex: 1;">
                                    <strong>${item.product_name}</strong><br>
                                    <small class="text-muted">${item.variant_title}</small><br>
                                    <small class="text-success"><?= $country['currency_symbol'] ?>${item.final_price} each</small>
                                </div>
                                <button class="btn btn-sm btn-danger" onclick="POS.removeFromCart(${index})">
                                    <i class="fi fi-br-circle-xmark"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="quantity-control">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="POS.updateQuantity(${index}, -1)">
                                        <i class="fi fi-br-minus"></i>
                                    </button>
                                    <input type="number" class="form-control form-control-sm text-center" 
                                           value="${item.quantity}" min="1" style="width: 60px;" readonly>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="POS.updateQuantity(${index}, 1)">
                                        <i class="fi fi-br-plus"></i>
                                    </button>
                                </div>
                                <strong><?= $country['currency_symbol'] ?>${itemTotal}</strong>
                            </div>
                        </div>
                    `;
                });
                $('#cartItems').html(html);
            },

            updateQuantity(index, change) {
                this.cart[index].quantity += change;
                if (this.cart[index].quantity < 1) {
                    this.cart[index].quantity = 1;
                }
                if (this.cart[index].is_unlimited_stock == 0 && 
                    this.cart[index].quantity > this.cart[index].stock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Insufficient Stock!',
                        text: 'Not enough items in stock to complete this order.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    this.cart[index].quantity -= change;
                    return;
                }
                this.renderCart();
                this.updateCartSummary();
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
                if (this.cartSelectedIndex >= this.cart.length) {
                    this.cartSelectedIndex = this.cart.length - 1;
                }
                this.renderCart();
                this.updateCartSummary();
            },

            clearCart() {
                if (confirm('Are you sure you want to clear the cart?')) {
                    this.cart = [];
                    this.additionalCharges = [];
                    this.selectedCustomer = null;
                    this.currentTab = null;
                    this.cartSelectedIndex = -1;
                    $('#additionalDiscount').val('');
                    $('#selectedCustomer').html('');
                    this.renderCart();
                    this.renderAdditionalCharges();
                    this.updateCartSummary();
                    this.renderHoldTabs();
                }
            },

            clearCartWithoutPermission() {
                this.cart = [];
                this.additionalCharges = [];
                this.selectedCustomer = null;
                this.currentTab = null;
                this.cartSelectedIndex = -1;
                $('#additionalDiscount').val('');
                $('#selectedCustomer').html('');
                this.renderCart();
                this.renderAdditionalCharges();
                this.updateCartSummary();
                this.renderHoldTabs();
            },

            addAdditionalCharge() {
                const name = $('#chargeName').val().trim();
                const amount = parseFloat($('#chargeAmount').val()) || 0;

                if (!name || amount <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        text: 'Please enter a valid charge name and amount.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                this.additionalCharges.push({ name, amount });
                $('#chargeName').val('');
                $('#chargeAmount').val('');
                this.renderAdditionalCharges();
                this.updateCartSummary();
            },

            renderAdditionalCharges() {
                if (this.additionalCharges.length === 0) {
                    $('#additionalChargesList').html('');
                    return;
                }

                let html = '';
                this.additionalCharges.forEach((charge, index) => {
                    html += `
                        <div class="additional-charge-item d-flex justify-content-between align-items-center">
                            <span>${charge.name}: <?= $country['currency_symbol'] ?>${charge.amount.toFixed(2)}</span>
                            <button class="btn btn-sm btn-danger" onclick="POS.removeCharge(${index})">
                                <i class="fi fi-br-circle-xmark"></i>
                            </button>
                        </div>
                    `;
                });
                $('#additionalChargesList').html(html);
            },

            removeCharge(index) {
                this.additionalCharges.splice(index, 1);
                this.renderAdditionalCharges();
                this.updateCartSummary();
            },

            updateCartSummary() {
                let subtotal = 0;
                let totalTax = 0;

                this.cart.forEach(item => {
                    const itemSubtotal = item.final_price * item.quantity;
                    subtotal += itemSubtotal;
                    totalTax += item.tax_amount * item.quantity;
                });

                const discountAmount = this.calculateDiscount(subtotal);
                const chargesTotal = this.additionalCharges.reduce((sum, charge) => sum + charge.amount, 0);
                const grandTotal = subtotal + totalTax - discountAmount + chargesTotal;

                $('#subtotal').text(`<?= $country['currency_symbol'] ?>${subtotal.toFixed(2)}`);
                $('#totalTax').text(`<?= $country['currency_symbol'] ?>${totalTax.toFixed(2)}`);
                $('#totalDiscount').text(`-<?= $country['currency_symbol'] ?>${discountAmount.toFixed(2)}`);
                $('#totalAdditionalCharges').text(`<?= $country['currency_symbol'] ?>${chargesTotal.toFixed(2)}`);
                $('#grandTotal').text(`<?= $country['currency_symbol'] ?>${grandTotal.toFixed(2)}`);
            },

            calculateDiscount(subtotal) {
                const discountValue = parseFloat($('#additionalDiscount').val()) || 0;
                const discountType = $('#discountType').val();

                if (discountValue <= 0) return 0;

                if (discountType === 'percentage') {
                    return Math.min((subtotal * discountValue) / 100, subtotal);
                } else {
                    return Math.min(discountValue, subtotal);
                }
            },

            searchCustomers(keyword) {
                if (keyword.length < 2) {
                    $('#customerSearchResults').hide();
                    this.customerSearchSelectedIndex = -1;
                    return;
                }

                $.get('/admin/pos/searchCustomer', { keyword: keyword }, (response) => {
                    if (response.success && response.customers.length > 0) {
                        let html = '';
                        response.customers.forEach(customer => {
                            html += `
                                <div class="search-result-item" onclick="POS.selectCustomer(${customer.id}, '${customer.name.replace(/'/g, "\\'")}', '${customer.mobile}')">
                                    <strong>${customer.name}</strong><br>
                                    <small class="text-muted">${customer.mobile}</small>
                                </div>
                            `;
                        });
                        $('#customerSearchResults').html(html).show();
                        this.customerSearchSelectedIndex = -1;
                    } else {
                        $('#customerSearchResults').html('<div class="p-3 text-muted">No customers found</div>').show();
                        this.customerSearchSelectedIndex = -1;
                    }
                });
            },

            selectCustomer(id, name, mobile) {
                this.selectedCustomer = { id, name, mobile };
                $('#customerSearch').val('');
                $('#customerSearchResults').hide();
                this.customerSearchSelectedIndex = -1;
                $('#selectedCustomer').html(`
                    <span class="customer-badge">
                        <i class="fi fi-br-user"></i> ${name} - ${mobile}
                        <i class="fi fi-br-circle-xmark ml-2" onclick="POS.removeCustomer()" style="cursor: pointer;"></i>
                    </span>
                `);
            },

            removeCustomer() {
                this.selectedCustomer = null;
                $('#selectedCustomer').html('');
            },

            showAddCustomerModal() {
                $('#addCustomerModal').modal('show');
            },

            saveQuickCustomer() {
                const name = $('#quickCustomerName').val().trim();
                const mobile = $('#quickCustomerMobile').val().trim();

                if (!name || !mobile) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        text: 'Please enter customer name and mobile',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                this.selectCustomer(null, name, mobile);
                $('#addCustomerModal').modal('hide');
                $('#quickCustomerName').val('');
                $('#quickCustomerMobile').val('');
            },

            holdOrder() {
                if (this.cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Empty Cart',
                        text: 'Your cart is empty!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (!this.selectedSeller) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Seller Selected',
                        text: 'Please select a seller first!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const sessionId = this.currentTab !== null && this.holdOrders[this.currentTab] ? 
                    this.holdOrders[this.currentTab].session_id : 
                    'hold_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

                const holdData = {
                    session_id: sessionId,
                    seller_id: this.selectedSeller,
                    user_id: this.selectedCustomer?.id || null,
                    customer_name: this.selectedCustomer?.name || null,
                    customer_mobile: this.selectedCustomer?.mobile || null,
                    cart_items: this.cart,
                    additional_discount: parseFloat($('#additionalDiscount').val()) || 0,
                    additional_discount_type: $('#discountType').val(),
                    additional_charges: this.additionalCharges
                };

                $.ajax({
                    url: '/admin/pos/saveCartSession',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(holdData),
                    success: (response) => {
                        if (response.success) {
                            this.loadHoldOrders();
                            this.clearCartWithoutPermission();
                            this.currentTab = null;
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Held',
                                text: 'Order held successfully!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Order Hold Failed',
                                text: response.message,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error holding order.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            },

            renderHoldTabs() {
                if (this.holdOrders.length === 0) {
                    $('#holdTabs').hide();
                    return;
                }

                let html = '';
                this.holdOrders.forEach((order, index) => {
                    const label = order.customer_name || `Order ${index + 1}`;
                    html += `
                        <span class="hold-tab ${this.currentTab === index ? 'active' : ''}" 
                              onclick="POS.loadHoldOrder(${index})">
                            ${label}
                            <i class="fi fi-br-circle-xmark close-tab" onclick="event.stopPropagation(); POS.removeHoldOrder(${index});"></i>
                        </span>
                    `;
                });
                $('#holdTabs').html(html).show();
            },

            loadHoldOrder(index) {
                const order = this.holdOrders[index];
                
                $.get('/admin/pos/getCartSession', { session_id: order.session_id }, (response) => {
                    if (response.success && response.session) {
                        const session = response.session;
                        
                        this.currentTab = index;
                        this.cart = session.cart_data || [];
                        this.additionalCharges = session.additional_charges || [];
                        this.selectedSeller = session.seller_id;
                        this.cartSelectedIndex = -1;
                        
                        $('#sellerSelect').val(session.seller_id).trigger('change');
                        $('#additionalDiscount').val(session.additional_discount || 0);
                        $('#discountType').val(session.additional_discount_type || 'flat');
                        
                        if (session.user_id) {
                            this.selectCustomer(session.user_id, session.customer_name, session.customer_mobile);
                        } else if (session.customer_name) {
                            this.selectCustomer(null, session.customer_name, session.customer_mobile);
                        } else {
                            this.removeCustomer();
                        }

                        this.renderCart();
                        this.renderAdditionalCharges();
                        this.updateCartSummary();
                        this.renderHoldTabs();
                    }
                });
            },

            removeHoldOrder(index) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Remove Held Order?',
                    text: 'Are you sure you want to remove this held order?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const order = this.holdOrders[index];
                        
                        $.post('/admin/pos/deleteCartSession', { session_id: order.session_id }, () => {
                            this.holdOrders.splice(index, 1);
                            
                            if (this.currentTab === index) {
                                this.clearCart();
                                this.currentTab = null;
                            } else if (this.currentTab > index) {
                                this.currentTab--;
                            }
                            
                            this.renderHoldTabs();
                        });
                    }
                });
            },

            loadHoldOrders() {
                if (!this.selectedSeller) return;
                
                $.get('/admin/pos/getCartSessions', { seller_id: this.selectedSeller }, (response) => {
                    if (response.success && response.sessions) {
                        this.holdOrders = response.sessions;
                        this.renderHoldTabs();
                    }
                });
            },

            placeOrder() {
                if (this.cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Empty Cart',
                        text: 'Your cart is empty!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (!this.selectedSeller) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Seller Selected',
                        text: 'Please select a seller first!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const orderData = {
                    seller_id: this.selectedSeller,
                    user_id: this.selectedCustomer?.id || null,
                    customer_name: this.selectedCustomer?.name || null,
                    customer_mobile: this.selectedCustomer?.mobile || null,
                    cart_items: this.cart,
                    additional_discount: parseFloat($('#additionalDiscount').val()) || 0,
                    additional_discount_type: $('#discountType').val(),
                    additional_charges: this.additionalCharges,
                    payment_method_id: $('#paymentMethod').val(),
                    session_id: this.currentTab !== null && this.holdOrders[this.currentTab] ? 
                        this.holdOrders[this.currentTab].session_id : null
                };

                $.ajax({
                    url: '/admin/pos/placeOrder',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(orderData),
                    success: (response) => {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Placed!',
                                text: 'Order placed successfully! Order ID: ' + response.order_id,
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, print invoice',
                                cancelButtonText: 'No, thanks'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const printUrl = '/admin/pos/printInvoice?order_id=' + response.db_order_id;
                                    window.open(printUrl, '_blank', 'width=800,height=600');
                                }
                        
                                if (this.currentTab !== null) {
                                    this.holdOrders.splice(this.currentTab, 1);
                                    this.currentTab = null;
                                }
                        
                                this.clearCartWithoutPermission();
                                this.loadHoldOrders();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    },
                    error: (xhr) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Placing Order',
                            text: xhr.responseJSON?.message || 'Unknown error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        };

        $(document).ready(() => {
            POS.init();
        });
    </script>
</body>
</html>