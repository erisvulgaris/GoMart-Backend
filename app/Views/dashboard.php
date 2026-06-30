<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>

</head>

<body class="sidebar-mini accent control-sidebar-slide-open text-sm layout-fixed <?php echo $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">

    <?php if($settings['demo_mode'] == 1):?>
    <!-- Top Banner -->
    <div class="top-banner" id="topBanner">
        <div class="banner-content">
            <span>This is a demo website — Buy genuine Groceryhub using our official link!</span>
            <a href="#" class="banner-button">Click Now</a>
            <a href="#" class="banner-button">Buy Now</a>
        </div>
        <button class="close-banner" onclick="closeBanner()"><i class="fas fa-times"></i></button>
    </div>
    <?php endif;?>

    <div class="wrapper">
        <?= $this->include('template/header') ?>
        <?= $this->include('template/sidebar') ?>

        <div class="content-wrapper" style="padding: 0 16px 24px;">

            <section class="content">
                <div class="container-fluid">

                    <!-- Page Header -->
                    <div class="dash-page-header">
                        <h4><i class="fas fa-tachometer-alt mr-2" style="color:#8e44ad;"></i>Dashboard</h4>
                        <div class="dash-date"><?= date('l, d F Y') ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xl-7 col-lg-7">

                            <!-- Stats grid -->
                            <div class="section-label">Overview</div>
                            <div class="row">
                                <div class="col-md-4 col-6">
                                    <a href="/admin/users" class="stat-card sc-blue">
                                        <div class="sc-icon"><i class="fi fi-tr-member-list"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Total Users</div>
                                            <div class="sc-value"><?= $totalUsers ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/category" class="stat-card sc-yellow">
                                        <div class="sc-icon"><i class="fi fi-tr-rectangle-list"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Categories</div>
                                            <div class="sc-value"><?= $totalCategories ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/subcategory" class="stat-card sc-maroon">
                                        <div class="sc-icon"><i class="fi fi-tr-rectangle-list"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Subcategories</div>
                                            <div class="sc-value"><?= $totalSubcategories ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/product-list" class="stat-card sc-orange">
                                        <div class="sc-icon"><i class="fi fi-tr-box-open"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Products</div>
                                            <div class="sc-value"><?= $totalProducts ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/orders" class="stat-card sc-navy">
                                        <div class="sc-icon"><i class="fi fi-tr-shipping-fast"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Total Orders</div>
                                            <div class="sc-value"><?= $totalOrders ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/orders" class="stat-card sc-green">
                                        <div class="sc-icon"><i class="fi fi-tr-dolly-flatbed-alt"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Completed</div>
                                            <div class="sc-value"><?= $deliveredOrders ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/orders" class="stat-card sc-purple">
                                        <div class="sc-icon"><i class="fi fi-tr-shipping-fast"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Pending Orders</div>
                                            <div class="sc-value"><?= $pendingOrders ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/orders" class="stat-card sc-red">
                                        <div class="sc-icon"><i class="fi fi-tr-cart-arrow-down"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Cancelled</div>
                                            <div class="sc-value"><?= $shippedOrders ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/stock-management?stock=0" class="stat-card sc-maroon" style="display:flex;">
                                        <div class="sc-icon"><i class="fi fi-tr-box-open-full"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Out of Stock</div>
                                            <div class="sc-value"><?= $outOfStockCount ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-6">
                                    <a href="/admin/stock-management?stock=2" class="stat-card sc-yellow" style="display:flex;">
                                        <div class="sc-icon"><i class="fi fi-tr-boxes"></i></div>
                                        <div class="sc-body">
                                            <div class="sc-label">Low Stock</div>
                                            <div class="sc-value"><?= $lowStockCount ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div><!-- /row -->

                            <!-- Action Needed section -->
                            <div class="section-label mt-2">Action Needed</div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <a href="/admin/seller/list" class="action-card ac-teal">
                                        <div class="ac-icon"><i class="fi fi-tr-store-alt"></i></div>
                                        <div class="ac-body">
                                            <div class="ac-label">New Sellers (Pending)</div>
                                            <div class="ac-value"><?= $newSellerCount ?></div>
                                        </div>
                                        <span class="ac-badge ac-teal-badge">Approve / Reject</span>
                                        <span class="ac-arrow"><i class="fas fa-chevron-right"></i></span>
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a href="/admin/delivery_boy/view" class="action-card ac-indigo">
                                        <div class="ac-icon"><i class="fi fi-tr-bike"></i></div>
                                        <div class="ac-body">
                                            <div class="ac-label">New Delivery Boys</div>
                                            <div class="ac-value"><?= $newDeliveryBoyCount ?></div>
                                        </div>
                                        <span class="ac-badge ac-indigo-badge">Active / Inactive</span>
                                        <span class="ac-arrow"><i class="fas fa-chevron-right"></i></span>
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a href="/admin/orders" class="action-card ac-cyan">
                                        <div class="ac-icon"><i class="fi fi-tr-shopping-cart"></i></div>
                                        <div class="ac-body">
                                            <div class="ac-label">New Orders</div>
                                            <div class="ac-value"><?= $newOrderCount ?></div>
                                        </div>
                                        <span class="ac-badge ac-cyan-badge">Quick View</span>
                                        <span class="ac-arrow"><i class="fas fa-chevron-right"></i></span>
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a href="/admin/product-list" class="action-card ac-amber">
                                        <div class="ac-icon"><i class="fi fi-tr-feedback-review"></i></div>
                                        <div class="ac-body">
                                            <div class="ac-label">Pending Ratings</div>
                                            <div class="ac-value"><?= $pendingRatingsCount ?></div>
                                        </div>
                                        <span class="ac-badge ac-amber-badge">Approve / Reject</span>
                                        <span class="ac-arrow"><i class="fas fa-chevron-right"></i></span>
                                    </a>
                                </div>
                            </div><!-- /row action -->

                        </div><!-- /col-xl-7 -->



                        <div class="col-xl-5 col-lg-5">

                            <!-- Today's Sales -->
                            <div class="sales-summary-card">
                                <div class="ssc-header"><h6>Total Sales Today</h6></div>
                                <div class="ssc-body">
                                    <div class="ssc-amount"><?= $country['currency_symbol'] ?><?= number_format($total_sales_today, 2) ?></div>
                                    <div class="ssc-diff">
                                        <?php if ($isIncrease): ?>
                                            <span class="up">▲ <?= $country['currency_symbol'] ?><?= number_format($sales_difference, 2) ?> (<?= $sales_percentage ?>%)</span>
                                        <?php else: ?>
                                            <span class="down">▼ <?= $country['currency_symbol'] ?><?= number_format($sales_difference, 2) ?> (<?= $sales_percentage ?>%)</span>
                                        <?php endif; ?>
                                        vs same day last week
                                    </div>
                                    <div id="salesChart" style="margin-top:8px;"></div>
                                </div>
                            </div>

                            <!-- Sales by Location -->
                            <div class="dash-card">
                                <div class="dc-header">
                                    <h6><i class="fas fa-map-marker-alt mr-1" style="color:#e74c3c;"></i>Sales by Location</h6>
                                </div>
                                <div class="dc-body" style="padding-top:8px;">
                                    <table class="loc-table">
                                        <tbody>
                                            <?php foreach ($salesByLocation as $location) : ?>
                                            <tr>
                                                <td><?= esc($location['city_name']) ?: 'Unknown' ?></td>
                                                <td class="loc-amount"><?= $country['currency_symbol'] ?><?= number_format($location['total_sales'] / 1000, 1) ?>K</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Avg. Order Value -->
                            <div class="dash-card">
                                <div class="dc-header">
                                    <h6><i class="fas fa-chart-pie mr-1" style="color:#8e44ad;"></i>Avg. Completed Order Value</h6>
                                </div>
                                <div class="dc-body" style="padding-top:8px;">
                                    <canvas id="avgOrderValueGauge" style="max-width:200px;display:block;margin:0 auto;"></canvas>
                                    <div class="avg-val"><?= $country['currency_symbol'] ?><?= $averageOrderValue ?></div>
                                </div>
                            </div>

                        </div><!-- /col-xl-5 -->

                    </div><!-- /row top -->



                    <div class="row">
                        <div class="col-md-6">
                            <div class="dash-card">
                                <div class="dc-header">
                                    <h6><i class="fas fa-chart-area mr-1" style="color:#3498db;"></i>Orders — <?= date('M Y') ?></h6>
                                </div>
                                <div class="dc-body" style="padding:8px 10px;" id="chart"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dash-card">
                                <div class="dc-header">
                                    <h6><i class="fas fa-chart-area mr-1" style="color:#27ae60;"></i>Orders — <?= date('Y') ?></h6>
                                </div>
                                <div class="dc-body" style="padding:8px 10px;" id="chart1"></div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="dash-card">
                                <div class="dc-header">
                                    <h6><i class="fas fa-shopping-bag mr-1" style="color:#e67e22;"></i>New Orders</h6>
                                </div>
                                <div class="dc-body" style="padding:0 8px 8px;">
                                    <table id="view_order" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Details</th>
                                                <th>O. Date</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dash-card">
                                <div class="dc-header">
                                    <h6><i class="fas fa-store mr-1" style="color:#16a085;"></i>Top Sellers</h6>
                                </div>
                                <div class="dc-body" style="padding:0 8px 8px;">
                                    <table id="view_seller" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Seller Name</th>
                                                <th>Store Name</th>
                                                <th>Total Revenue</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /container-fluid -->
            </section>

        </div><!-- /content-wrapper -->

        <?= $this->include('template/footer') ?>
    </div><!-- /wrapper -->

    <?= $this->include('template/script') ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gaugeJS/dist/gauge.min.js"></script>
    <script>
        function closeBanner() {
            document.getElementById('topBanner').classList.add('hidden');
        }

        function monthWiseOrder() {
            var categories = <?php echo $categories; ?>;
            var data       = <?php echo $data; ?>;
            new ApexCharts(document.querySelector("#chart"), {
                chart: { height: 250, type: "area", toolbar: { show: true, tools: { download: false } } },
                dataLabels: { enabled: false },
                series: [{ name: "Orders", data: data }],
                fill: { colors: ['#8e44ad'] },
                colors: ['#8e44ad'],
                xaxis: { categories: categories },
                grid: { borderColor: '#eee' },
            }).render();
        }

        function yearWiseOrder() {
            var categories = <?php echo $categoriesMonthWise; ?>;
            var data       = <?php echo $dataMonthWise; ?>;
            new ApexCharts(document.querySelector("#chart1"), {
                chart: { height: 250, type: "area", toolbar: { show: true, tools: { download: false } } },
                dataLabels: { enabled: false },
                series: [{ name: "Orders", data: data }],
                fill: { colors: ['#27ae60'] },
                colors: ['#27ae60'],
                xaxis: { categories: categories },
                grid: { borderColor: '#eee' },
            }).render();
        }

        function salesChart() {
            new ApexCharts(document.querySelector("#salesChart"), {
                chart: { type: "line", height: 120, toolbar: { show: false }, sparkline: { enabled: true } },
                series: [
                    { name: "This Month", data: <?= $totalsThisMonth; ?> },
                    { name: "Last Month", data: <?= $totalsLastMonth; ?> }
                ],
                xaxis: { categories: <?= $weeks ?>, labels: { show: false } },
                yaxis: { labels: { style: { colors: "#666" } } },
                colors: ["#3498db", "#bdc3c7"],
                stroke: { width: 2 },
                tooltip: { x: { show: false } },
            }).render();
        }

        function avgOrderValueGauge() {
            var opts = {
                angle: 0, lineWidth: 0.2, radiusScale: 1,
                pointer: { length: 0.6, strokeWidth: 0.05, color: '#000', cap: 'round' },
                limitMax: false, limitMin: false,
                colorStart: "#ff8c00", colorStop: "#ff8c00",
                strokeColor: "#000", generateGradient: true, highDpiSupport: true,
                staticLabels: { font: "14px Arial", labels: [0, <?= $averageOrderValue / 1.5 ?>], color: "#000", fractionDigits: 0 },
                staticZones: [
                    { strokeStyle: "#FFC107", min: 0,                            max: <?= $averageOrderValue / 3 ?> },
                    { strokeStyle: "#008FFB", min: <?= $averageOrderValue / 3 ?>, max: <?= $averageOrderValue ?> },
                    { strokeStyle: "#00897B", min: <?= $averageOrderValue ?>,     max: <?= $averageOrderValue * 1.5 ?> }
                ],
                animationSpeed: 35, renderTicks: true
            };
            var target = document.getElementById('avgOrderValueGauge');
            var gauge  = new Gauge(target).setOptions(opts);
            gauge.maxValue = <?= $averageOrderValue * 1.5 ?>;
            gauge.setMinValue(0);
            gauge.set(<?= $averageOrderValue ?>);
        }

        $(document).ready(function() {
            monthWiseOrder();
            yearWiseOrder();
            salesChart();
            avgOrderValueGauge();
        });
    </script>
    <script src="<?= base_url() . "assets/page-script/dashboard.js" ?>"></script>

</body>
</html>
