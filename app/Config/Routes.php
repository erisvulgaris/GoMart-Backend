<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/firebase-messaging-sw.js', 'ServiceWorkerController::firebaseMessagingSW');

$routes->get('/init', 'AppInit::index');
$routes->post('/init/process', 'AppInit::process');

$routes->get('/testSession', 'Test::testSession');


///////////////////// seller Panel routes //////////////////////

$routes->get('/seller', 'Seller\Auth::login');
$routes->get('/seller/auth/login', 'Seller\Auth::login');
$routes->get('/seller/login', 'Seller\Auth::login');
$routes->get('/seller/auth/logout', 'Seller\Auth::logout');
$routes->post('/seller/auth/processLogin', 'Seller\Auth::processLogin');
$routes->get('/seller/dashboard', 'Seller\Dashboard::index');
$routes->get('/seller/forgot-password/(:any)', 'Seller\Auth::forgotPassword/$1');
$routes->post('/seller/auth/forgot-password/send-link', 'Seller\Auth::sendLink');
$routes->get('/seller/auth/forgot-password/link', 'Seller\Auth::resetPasswordLink');
$routes->post('/seller/auth/reset-password', 'Seller\Auth::resetPassword');
$routes->get('/seller/auth/change-password', 'Seller\Auth::changePassword');
$routes->post('/seller/auth/change-password/update', 'Seller\Auth::updatePassword');

$routes->get('/seller/category', 'Seller\Category::index');
$routes->post('/seller/category/list', 'Seller\Category::list');

$routes->get('/seller/subcategory', 'Seller\Subcategory::index');
$routes->post('/seller/subcategory/list', 'Seller\Subcategory::list');

$routes->get('/seller/brand', 'Seller\Brand::index');
$routes->post('/seller/brand/list', 'Seller\Brand::list');

$routes->get('/seller/product', 'Seller\Product::index');
$routes->get('/seller/product-list', 'Seller\Product::view');
$routes->post('/seller/product/add', 'Seller\Product::add');
$routes->post('/seller/product/list', 'Seller\Product::list');
$routes->get('/seller/product/edit/(:any)', 'Seller\Product::edit/$1');
$routes->post('/seller/product/update', 'Seller\Product::update');
$routes->post('/seller/product/delete', 'Seller\Product::delete');
$routes->get('/seller/product/bulk-import', 'Seller\Product::bulkImport');
$routes->get('/seller/product/bulk-update', 'Seller\Product::bulkUpdate');
$routes->get('/seller/product/bulk-import', 'Seller\Product::bulkImport');
$routes->post('/seller/product/bulk-import/insert', 'Seller\Product::bulkImportFile');
$routes->post('/seller/product/bulk-update/update', 'Seller\Product::bulkUpdateFile');
$routes->get('/seller/product/bulk-update/download', 'Seller\Product::exportProductsInCSV');
$routes->get('/seller/product_order', 'Seller\Product::productOrder');
$routes->post('/seller/product_order/update', 'Seller\Product::productOrderUpdate');
$routes->post('/seller/product/get_product_by_category', 'Seller\Product::productByCategory');
$routes->get('/seller/product/view/(:any)', 'Seller\Product::viewProduct/$1');
$routes->post('/seller/product/delete-other-image', 'Seller\Product::deleteOtherImage');
$routes->post('/seller/product/generateDescription', 'Seller\Product::generateDescription');
$routes->post('/seller/product/generateSeoContent', 'Seller\Product::generateSeoContent');
$routes->post('/seller/product/generate-qr', 'Seller\Product::generateQrCode');


$routes->get('/seller/taxes', 'Seller\Tax::index');
$routes->post('/seller/taxes/list', 'Seller\Tax::list');

$routes->post('/seller/notification/token/update', 'Seller\DeviceToken::tokenUpdate');


$routes->get('/seller/orders', 'Seller\Orders::index');
$routes->post('/seller/orders/list', 'Seller\Orders::list');
$routes->get('/seller/orders/view/(:any)', 'Seller\Orders::view/$1');
$routes->post('/seller/orders/list/(:any)', 'Seller\Orders::listOrderWithLimit/$1');

$routes->post('/seller/orders/assignDeliveryBoy', 'Seller\Orders::assignDeliveryBoy');
$routes->post('/seller/orders/getDetailsById', 'Seller\Orders::getDetailsById');
$routes->post('/seller/orders/update_status', 'Seller\Orders::updateOrderStatus');
$routes->get('/seller/orders/orderDetailsTableById/(:any)', 'Seller\Orders::orderDetailsTable/$1');
$routes->post('/seller/orders/download_invoice', 'Seller\Orders::downloadInvoice');
$routes->post('/seller/orders/delivery_date/update', 'Seller\Orders::updateDeliveryDate');

$routes->get('/seller/stock-management', 'Seller\StockMangement::index');
$routes->post('/seller/stock-management/list', 'Seller\StockMangement::list');
$routes->post('/seller/stock-management/updateStock', 'Seller\StockMangement::update');

$routes->get('/seller/wallet-transaction', 'Seller\WalletTransaction::transactionListView');
$routes->post('/seller/wallet-transaction/list', 'Seller\WalletTransaction::transactionList');
$routes->get('/seller/withdrawal-request', 'Seller\WalletTransaction::withdrawalRequestView');
$routes->post('/seller/withdrawal-request/list', 'Seller\WalletTransaction::withdrawalRequestList');
$routes->post('/seller/withdrawal-request/add-request', 'Seller\WalletTransaction::addTransaction');


$routes->get('/seller/product-selling-report', 'Seller\Report::productSellingReport');
$routes->post('/seller/product-selling-report/list', 'Seller\Report::productSellingReportlist');

$routes->get('/seller/selling-report', 'Seller\Report::sellingReport');
$routes->post('/seller/selling-report/list', 'Seller\Report::sellingReportList');

$routes->get('/seller/return-request', 'Seller\ReturnRequest::returnRequest');
$routes->post('/seller/return-request/list', 'Seller\ReturnRequest::returnRequestList');
$routes->post('/seller/return-request/view', 'Seller\ReturnRequest::viewReturnRequest');
$routes->post('/seller/return-request/update', 'Seller\ReturnRequest::updateReturnRequest');


$routes->post('/seller/tags/get-tags', 'Seller\Tags::getTags');

$routes->get('/seller/pos', 'Seller\Pos::index');
$routes->get('/seller/pos/getTopProducts', 'Seller\Pos::getTopProducts');
$routes->get('/seller/pos/searchProducts', 'Seller\Pos::searchProducts');
$routes->get('/seller/pos/getProductDetails', 'Seller\Pos::getProductDetails');
$routes->get('/seller/pos/searchCustomer', 'Seller\Pos::searchCustomer');
$routes->post('/seller/pos/saveCartSession', 'Seller\Pos::saveCartSession');
$routes->get('/seller/pos/getCartSession', 'Seller\Pos::getCartSession');
$routes->post('/seller/pos/deleteCartSession', 'Seller\Pos::deleteCartSession');
$routes->post('/seller/pos/placeOrder', 'Seller\Pos::placeOrder');
$routes->get('/seller/pos/getCartSessions', 'Seller\Pos::getCartSessions');
$routes->get('/seller/pos/printInvoice', 'Seller\Pos::printInvoice');
$routes->get('/seller/pos-report', 'Seller\Pos::reportIndex');
$routes->post('/seller/pos-report/list', 'Seller\Pos::reportList');


////////////////// website routes
$routes->get('/', 'Website\Home::index');
$routes->post('/home/screen-data', 'Website\Home::getHomeScreenData');

$routes->get('/googlesignin', 'Website\Auth::googleSignin');
$routes->get('/mobileLogin', 'Website\Auth::mobileLogin');
$routes->post('/mobileLogin', 'Website\Auth::mobileLogin');
$routes->get('/mobileOtp', 'Website\Auth::mobileOtp');
$routes->post('/mobileOtp', 'Website\Auth::mobileOtp');

$routes->get('/category', 'Website\Category::index');
$routes->get('/category/(:segment)', 'Website\Category::categoryProductList/$1');
$routes->get('/brand', 'Website\Brand::index');
$routes->get('/brand/(:any)', 'Website\Product::getBrandProductList/$1');

$routes->get('/sellers/(:any)', 'Website\Sellers::getSellerPage/$1');
$routes->get('/seller/(:any)', 'Website\Product::getSellerProductList/$1');
$routes->get('/product/variants/(:any)', 'Website\Product::getProductWithVariants/$1');

$routes->get('/popular-products', 'Website\Product::getPopularProductWithVariants');
$routes->get('/deal-of-the-day-products', 'Website\Product::getDealoftheDayProductWithVariants');

$routes->get('/product/(:any)', 'Website\Product::getProductDetails/$1');
$routes->get('/subcategory/(:segment)', 'Website\Subcategory::subcategoryProductList/$1');
$routes->get('/no-product-avilable', 'Website\Home::noProductAvilable');

$routes->get('/search', 'Website\Search::index');
$routes->post('/search', 'Website\Search::index');
$routes->get('/search/popular', 'Website\Search::popularSearch');
$routes->post('/fetchDeliverableAreaByLatLong', 'Website\City::fetchDeliverableAreaByLatLong');
$routes->get('/testPointInPolygon', 'Website\City::testPointInPolygon');

$routes->get('/setLocalCity/(:any)', 'Website\City::setLocalCity/$1');
$routes->get('/login', 'Website\Auth::index');
$routes->post('/login', 'Website\Auth::index');
$routes->get('/signup', 'Website\Auth::signup');
$routes->post('/signup', 'Website\Auth::signup');
$routes->get('/signupOtp', 'Website\Auth::signupOtp');
$routes->post('/signupOtp', 'Website\Auth::signupOtp');
$routes->get('/resetPassword', 'Website\Auth::resetPassword');
$routes->post('/resetPassword', 'Website\Auth::resetPassword');
$routes->get('/resetPassword/link', 'Website\Auth::resetPasswordLink');
$routes->post('/resetPassword/link', 'Website\Auth::resetPasswordLink');
$routes->get('/logout', 'Website\Auth::logout');
$routes->get('/order-history', 'Website\Order::orderHistory');
$routes->post('/addToCart', 'Website\Cart::addToCart');
$routes->post('/removeFromCart', 'Website\Cart::removeFromCart');
$routes->post('/removeItem', 'Website\Cart::removeItem');
$routes->get('/cart', 'Website\Cart::cartItemList');
$routes->get('/cart/(:num)', 'Website\Cart::oneSellerCartItemList/$1');
$routes->post('/cartItemList', 'Website\Cart::fetchCartItemList');
$routes->post('/switchVarient', 'Website\Product::switchVarient');
$routes->get('/checkout', 'Website\Checkout::index');
$routes->get('/checkout/(:num)', 'Website\Checkout::oneSellerCartCheckout/$1');
$routes->post('/fetchIsInDeliveryArea', 'Website\DeliverableArea::fetchIsInDeliveryArea');
$routes->post('/saveAddress', 'Website\Address::saveAddress');
$routes->post('/deleteAddress', 'Website\Address::deleteAddress');
$routes->post('/activeAddress', 'Website\Address::activeAddress');
$routes->post('/getTimeSlot', 'Website\Timeslot::getTimeSlot');
$routes->post('/getCouponList', 'Website\Coupon::getCouponList');
$routes->post('/applyCoupon', 'Website\Coupon::applyCoupon');
$routes->post('/removeCoupon', 'Website\Coupon::removeCoupon');
$routes->post('/applyWallet', 'Website\Wallet::applyWallet');
$routes->post('/removeWallet', 'Website\Wallet::removeWallet');
$routes->post('/verifyOrderDetails', 'Website\Order::verifyOrderDetails');
$routes->post('/fetchAddressList', 'Website\Address::fetchAddressList');
$routes->post('/fetchAllAddressList', 'Website\Address::fetchAllAddressList');
$routes->get('/menu', 'Website\Menu::index');
$routes->post('/placeCODOrder', 'Website\Order::placeCODOrder');
$routes->get('/dashboard', 'Website\Dashboard::index');
$routes->get('/address', 'Website\Address::index');
$routes->get('/profile', 'Website\Profile::index');
$routes->get('/contact-us', 'Website\Home::contactUs');
$routes->get('/about-us', 'Website\Home::aboutUs');
$routes->get('/wallet', 'Website\Wallet::wallet');
$routes->get('/privacy-policy', 'Website\Home::privacyPolicy');
$routes->get('/terms-condition', 'Website\Home::termsCondition');
$routes->get('/refund-policy', 'Website\Home::refundPolicy');

$routes->get('/delivery/privacy-policy', 'Website\Home::privacyPolicyDelivery');
$routes->get('/delivery/terms-condition', 'Website\Home::termsConditionDelivery');
$routes->get('/delivery/about-us', 'Website\Home::termsConditionDelivery');

$routes->get('/faq', 'Website\Home::faq');
$routes->get('/order-details/(:any)', 'Website\Order::orderDetails/$1');
$routes->post('/fetchLiveDeliveryTracking', 'Website\Order::fetchLiveDeliveryTracking');
$routes->get('/notification', 'Website\Notification::notification');
$routes->post('/fetchCartItemCount', 'Website\Cart::fetchCartItemCount');
$routes->get('/product', 'Website\Product::index');
$routes->post('/fetchProductList', 'Website\Product::fetchProductList');
$routes->post('/fetchSubcategoryProductList', 'Website\Product::fetchSubcategoryProductList');
$routes->post('/changePassword', 'Website\Profile::changePassword');

$routes->post('/updateProfile', 'Website\Profile::updateProfile');
$routes->post('/deleteAccount', 'Website\Profile::deleteAccount');

$routes->post('/createRazorpayOrder', 'Website\Order::createRazorpayOrder');
$routes->post('/verifyRazorpayPayment', 'Website\Order::verifyRazorpayPayment');

$routes->post('/createPaypalOrder', 'Website\Order::createPaypalOrder');
$routes->post('/capturePaypalOrder', 'Website\Order::capturePaypalOrder');

$routes->post('/createPaystackOrder', 'Website\Order::createPaystackOrder');
$routes->post('/verifyPaystackOrder', 'Website\Order::verifyPaystackOrder');

$routes->post('/createCashFreeOrder', 'Website\Order::createCashFreeOrder');
$routes->post('/confirmCashFreeOrder', 'Website\Order::confirmCashFreeOrder');

$routes->post('/createStripeOrder', 'Website\Order::createStripeOrder');
$routes->post('/confirmStripeOrder', 'Website\Order::confirmStripeOrder');

$routes->post('/cancelOrder', 'Website\Order::cancelOrder');
$routes->post('/returningItemRequest', 'Website\Order::returningItemRequest');
$routes->post('/uploadUserProfilePic', 'Website\Profile::uploadUserProfilePic');
$routes->post('/downloadInvoice', 'Website\Order::downloadInvoice');
$routes->post('/writeReview', 'Website\Product::writeReview');
$routes->get('/language', 'Website\Language::index');
$routes->get('/language/(:any)', 'Website\Language::changeLanguage/$1');





///////// ADMIN ROUTE
$routes->get('/admin', 'Admin\Auth::login');
$routes->get('/admin/auth/login', 'Admin\Auth::login');
$routes->get('/admin/login', 'Admin\Auth::login');
$routes->get('/admin/auth/logout', 'Admin\Auth::logout');
$routes->post('/admin/auth/processLogin', 'Admin\Auth::processLogin');
$routes->get('/admin/dashboard', 'Admin\Dashboard::index');
$routes->get('/admin/forgot-password/(:any)', 'Admin\Auth::forgotPassword/$1');
$routes->post('/admin/auth/forgot-password/send-link', 'Admin\Auth::sendLink');
$routes->get('/admin/auth/forgot-password/link', 'Admin\Auth::resetPasswordLink');
$routes->post('/admin/auth/reset-password', 'Admin\Auth::resetPassword');
$routes->get('/admin/permission-not-allowed', 'Admin\Auth::permissionNotAllowed');


$routes->get('/admin/category', 'Admin\Category::index');

$routes->get('/admin/group-category', 'Admin\Category::group');
$routes->post('/admin/group-category/add', 'Admin\Category::groupAdd');
$routes->post('/admin/group-category/update/(:any)', 'Admin\Category::groupUpdate/$1');
$routes->post('/admin/group-category/list', 'Admin\Category::groupList');
$routes->get('/admin/group-category/edit/(:any)', 'Admin\Category::groupEdit/$1');
$routes->post('/admin/group-category/delete', 'Admin\Category::groupDelete');


$routes->get('/admin/header-category', 'Admin\Category::header');
$routes->post('/admin/header-category/add', 'Admin\Category::headerAdd');
$routes->post('/admin/header-category/update', 'Admin\Category::headerUpdate');
$routes->post('/admin/header-category/list', 'Admin\Category::headerList');
$routes->get('/admin/header-category/edit/(:any)', 'Admin\Category::headerEdit/$1');
$routes->post('/admin/header-category/delete', 'Admin\Category::headerDelete');


$routes->post('/admin/category/add', 'Admin\Category::add');
$routes->post('/admin/category/list', 'Admin\Category::list');
$routes->get('/admin/category/edit/(:any)', 'Admin\Category::edit/$1');
$routes->post('/admin/category/update', 'Admin\Category::update');
$routes->post('/admin/category/delete', 'Admin\Category::delete');
$routes->get('/admin/category_order', 'Admin\Category::categoryOrder');
$routes->post('/admin/category_order/update', 'Admin\Category::categoryOrderUpdate');


$routes->get('/admin/brand', 'Admin\Brand::index');
$routes->post('/admin/brand/add', 'Admin\Brand::add');
$routes->post('/admin/brand/list', 'Admin\Brand::list');
$routes->get('/admin/brand/edit/(:any)', 'Admin\Brand::edit/$1');
$routes->post('/admin/brand/update', 'Admin\Brand::update');
$routes->post('/admin/brand/delete', 'Admin\Brand::delete');
$routes->get('/admin/brand_order', 'Admin\Brand::brandOrder');
$routes->post('/admin/brand_order/update', 'Admin\Brand::brandOrderUpdate');

$routes->get('/admin/subcategory', 'Admin\Subcategory::index');
$routes->post('/admin/subcategory/add', 'Admin\Subcategory::add');
$routes->post('/admin/subcategory/list', 'Admin\Subcategory::list');
$routes->get('/admin/subcategory/edit/(:any)', 'Admin\Subcategory::edit/$1');
$routes->post('/admin/subcategory/update', 'Admin\Subcategory::update');
$routes->post('/admin/subcategory/delete', 'Admin\Subcategory::delete');
$routes->post('/admin/subcategory/getSub', 'Admin\Subcategory::getSub');
$routes->get('/admin/subcategory_order', 'Admin\Subcategory::subcategoryOrder');
$routes->post('/admin/subcategory_order/update', 'Admin\Subcategory::subcategoryOrderUpdate');


$routes->get('/admin/product', 'Admin\Product::index');
$routes->get('/admin/product-list', 'Admin\Product::view');
$routes->post('/admin/product/add', 'Admin\Product::add');
$routes->post('/admin/product/list', 'Admin\Product::list');
$routes->get('/admin/product/edit/(:any)', 'Admin\Product::edit/$1');
$routes->get('/admin/product/view/(:any)', 'Admin\Product::viewProduct/$1');
$routes->post('/admin/product/update', 'Admin\Product::update');
$routes->post('/admin/product/delete', 'Admin\Product::delete');
$routes->post('/admin/product/delete-variation', 'Admin\Product::deleteVariation');
$routes->get('/admin/product/bulk-import', 'Admin\Product::bulkImport');
$routes->get('/admin/product/bulk-update', 'Admin\Product::bulkUpdate');
$routes->get('/admin/product/bulk-import', 'Admin\Product::bulkImport');
$routes->post('/admin/product/bulk-import/insert', 'Admin\Product::bulkImportFile');
$routes->post('/admin/product/bulk-update/update', 'Admin\Product::bulkUpdateFile');
$routes->get('/admin/product/bulk-update/download', 'Admin\Product::exportProductsInCSV');
$routes->get('/admin/product_order', 'Admin\Product::productOrder');
$routes->post('/admin/product_order/update', 'Admin\Product::productOrderUpdate');
$routes->post('/admin/product/get_product_by_category', 'Admin\Product::productByCategory');
$routes->post('/admin/product/delete', 'Admin\Product::delete');
$routes->post('/admin/product/delete-other-image', 'Admin\Product::deleteOtherImage');
$routes->post('/admin/product/delete-variant-image', 'Admin\Product::deleteVariantImage');
$routes->get('/admin/product/request', 'Admin\Product::request');
$routes->post('/admin/product/request/list', 'Admin\Product::requestList');
$routes->post('/admin/product/request/update', 'Admin\Product::requestUpdate');
$routes->post('/admin/product/generateDescription', 'Admin\Product::generateDescription');
$routes->post('/admin/product/generateSeoContent', 'Admin\Product::generateSeoContent');
$routes->post('/admin/product/generate-qr', 'Admin\Product::generateQrCode');
$routes->get('/admin/product/rating/(:any)', 'Admin\Product::rating/$1');
$routes->post('/admin/product/rating/update', 'Admin\Product::updateRating');
$routes->get('/admin/product/copy', 'Admin\Product::copyProductFromSellerIndex');
$routes->post('/admin/product/get_category_by_seller', 'Admin\Product::getCategoryBySeller');
$routes->post('/admin/product/get_subcategory_by_category', 'Admin\Product::getSubcategoryByCategory');
$routes->post('/admin/product/get_product_by_seller_category_subcategory', 'Admin\Product::getProductBySellerCategorySubcategory');
$routes->post('/admin/product/copy_selected_products', 'Admin\Product::copySelectedProducts');


$routes->get('/admin/taxes', 'Admin\Tax::index');
$routes->post('/admin/taxes/list', 'Admin\Tax::list');
$routes->post('/admin/taxes/add', 'Admin\Tax::add');
$routes->get('/admin/taxes/edit/(:any)', 'Admin\Tax::edit/$1');
$routes->post('/admin/taxes/update', 'Admin\Tax::update');
$routes->post('/admin/taxes/delete', 'Admin\Tax::delete');

$routes->get('/admin/seller', 'Admin\Seller::index');
$routes->get('/admin/seller/list', 'Admin\Seller::view');
$routes->post('/admin/seller/list/view', 'Admin\Seller::list');
$routes->post('/admin/seller/list/view/top', 'Admin\Seller::topSellerList');
$routes->get('/admin/seller/payment_history', 'Admin\Seller::transactionListView');
$routes->post('/admin/seller/payment_history/list', 'Admin\Seller::transactionList');
$routes->post('/admin/seller/payment_history/add', 'Admin\Seller::addTransaction');
$routes->post('/admin/seller/add', 'Admin\Seller::add');
$routes->get('/admin/seller/edit/(:any)', 'Admin\Seller::edit/$1');
$routes->post('/admin/seller/update', 'Admin\Seller::update');
$routes->post('/admin/seller/delete', 'Admin\Seller::delete');
$routes->post('/admin/seller/delete-seller-category', 'Admin\Seller::deleteSellerCategory');



$routes->get('/admin/manage-city', 'Admin\ManageCity::index');
$routes->get('/admin/manage-city/edit/(:any)', 'Admin\ManageCity::edit/$1');
$routes->post('/admin/manage-city/add', 'Admin\ManageCity::addCity');
$routes->post('/admin/manage-city/delete', 'Admin\ManageCity::deleteCity');
$routes->post('/admin/manage-city/update', 'Admin\ManageCity::updateCity');
$routes->post('/admin/manage-city/get_city_list', 'Admin\ManageCity::get_city_list');

$routes->get('/admin/deliverable-area', 'Admin\DeliverableArea::index');
$routes->post('/admin/deliverable-area/add', 'Admin\DeliverableArea::add');
$routes->get('/admin/deliverable-area/view', 'Admin\DeliverableArea::view');
$routes->post('/admin/deliverable-area/viewlist', 'Admin\DeliverableArea::list');
$routes->post('/admin/deliverable-area/get-by-cityid', 'Admin\DeliverableArea::getByCityId');
$routes->get('/admin/deliverable-area/edit/(:any)', 'Admin\DeliverableArea::edit/$1');
$routes->post('/admin/deliverable-area/update', 'Admin\DeliverableArea::update');
$routes->post('/admin/deliverable-area/delete', 'Admin\DeliverableArea::delete'); 


$routes->get('/admin/coupon', 'Admin\Coupon::index');
$routes->post('/admin/coupon/add', 'Admin\Coupon::add');
$routes->post('/admin/coupon/list', 'Admin\Coupon::list');
$routes->post('/admin/coupon/delete', 'Admin\Coupon::delete');

$routes->get('/admin/banner', 'Admin\Banner::index');
$routes->post('/admin/banner/add', 'Admin\Banner::add');
$routes->get('/admin/banner/edit/(:any)', 'Admin\Banner::edit/$1');
$routes->post('/admin/banner/list', 'Admin\Banner::list');
$routes->post('/admin/banner/delete', 'Admin\Banner::delete');
$routes->post('/admin/banner/update', 'Admin\Banner::update');
$routes->get('/admin/banner/search-products', 'Admin\Banner::searchProducts');


$routes->get('/admin/timeslot', 'Admin\Timeslot::index');
$routes->post('/admin/timeslot/add', 'Admin\Timeslot::add');
$routes->post('/admin/timeslot/list', 'Admin\Timeslot::list');
$routes->post('/admin/timeslot/delete', 'Admin\Timeslot::delete');
$routes->post('/admin/timeslot/changeTimeslotStatus', 'Admin\Timeslot::changeTimeslotStatus');

$routes->get('/admin/home_section', 'Admin\HomeSection::index');
$routes->post('/admin/home_section/add', 'Admin\HomeSection::add');
$routes->post('/admin/home_section/subcategory', 'Admin\HomeSection::subcategory');
$routes->post('/admin/home_section/list', 'Admin\HomeSection::list');
$routes->post('/admin/home_section/delete', 'Admin\HomeSection::delete');
$routes->get('/admin/home_section/edit/(:any)', 'Admin\HomeSection::edit/$1');
$routes->post('/admin/home_section/update', 'Admin\HomeSection::update');

// Home Screens Management
$routes->get('/admin/home-screens', 'Admin\HomeScreen::index');
$routes->post('/admin/home-screens/list', 'Admin\HomeScreen::list');
$routes->post('/admin/home-screens/add', 'Admin\HomeScreen::add');
$routes->post('/admin/home-screens/update', 'Admin\HomeScreen::update');
$routes->post('/admin/home-screens/delete', 'Admin\HomeScreen::delete');

// Sections Management
$routes->get('/admin/sections', 'Admin\Section::index');
$routes->post('/admin/sections/list', 'Admin\Section::list');
$routes->post('/admin/sections/add', 'Admin\Section::add');
$routes->post('/admin/sections/update', 'Admin\Section::update');
$routes->post('/admin/sections/delete', 'Admin\Section::delete');
$routes->post('/admin/sections/toggle-status', 'Admin\Section::toggleStatus');
$routes->post('/admin/sections/update-sort-order', 'Admin\Section::updateSortOrder');
$routes->post('/admin/sections/get-manual-items', 'Admin\Section::getManualItems');
$routes->get('/admin/sections/search-products', 'Admin\Section::searchProducts');


$routes->get('/admin/notification', 'Admin\Notification::index');
$routes->post('/admin/notification/add', 'Admin\Notification::add');
$routes->post('/admin/notification/subcategory', 'Admin\Notification::subcategory');
$routes->post('/admin/notification/list', 'Admin\Notification::list');
$routes->post('/admin/notification/delete', 'Admin\Notification::delete');
$routes->post('/admin/notification/token/update', 'Admin\Notification::tokenUpdate');


$routes->post('/admin/users/get_search_user', 'Admin\User::get_search_user');
$routes->get('/admin/users', 'Admin\User::index');
$routes->post('/admin/users/add', 'Admin\User::add');
$routes->post('/admin/users/subcategory', 'Admin\User::subcategory');
$routes->post('/admin/users/list', 'Admin\User::list');
$routes->post('/admin/user/delete', 'Admin\User::delete');
$routes->post('/admin/user/get_wallet', 'Admin\User::getUserWallet');
$routes->post('/admin/user/user_wallet_list', 'Admin\User::userWalletList');
$routes->post('/admin/user/wallet/add_amount_by_id', 'Admin\User::addAmountById');
$routes->post('/admin/user/update_status', 'Admin\User::updateStatus');




$routes->get('/admin/orders', 'Admin\Orders::index');
$routes->post('/admin/orders/list', 'Admin\Orders::list');
$routes->post('/admin/orders/list/(:any)', 'Admin\Orders::listOrderWithLimit/$1');
$routes->get('/admin/orders/view/(:any)', 'Admin\Orders::view/$1');

$routes->get('/admin/orders/thermalPrint', 'Admin\Orders::thermalPrint');


$routes->post('/admin/orders/assignDeliveryBoy', 'Admin\Orders::assignDeliveryBoy');
$routes->post('/admin/orders/getDetailsById', 'Admin\Orders::getDetailsById');
$routes->post('/admin/orders/update_status', 'Admin\Orders::updateOrderStatus');
$routes->get('/admin/orders/orderDetailsTableById/(:any)', 'Admin\Orders::orderDetailsTable/$1');
$routes->post('/admin/orders/download_invoice', 'Admin\Orders::downloadInvoice');
$routes->get('/admin/return-request', 'Admin\ReturnRequest::returnRequest');
$routes->post('/admin/return-request/list', 'Admin\ReturnRequest::returnRequestList');
$routes->post('/admin/return-request/view', 'Admin\ReturnRequest::viewReturnRequest');
$routes->post('/admin/return-request/update', 'Admin\ReturnRequest::updateReturnRequest');
$routes->post('/admin/update-returned-status/view', 'Admin\ReturnRequest::viewReturnedToStoreRequest');
$routes->post('/admin/update-returned-status/update', 'Admin\ReturnRequest::updateReturnedToStoreRequest');
$routes->post('/admin/orders/delivery_date/update', 'Admin\Orders::updateDeliveryDate');


$routes->get('/admin/delivery_boy/fund_transfer', 'Admin\DeliveryBoy::fundTransfer');
$routes->post('/admin/delivery_boy/fund_transfer/add', 'Admin\DeliveryBoy::addFundTransfer');
$routes->post('/admin/delivery_boy/fund_transfer/list', 'Admin\DeliveryBoy::listFundTransfer');
$routes->get('/admin/delivery_boy/cash_collection', 'Admin\DeliveryBoy::cashCollection');
$routes->post('/admin/delivery_boy/cash_collection/add', 'Admin\DeliveryBoy::addCashCollection');
$routes->post('/admin/delivery_boy/cash_collection/list', 'Admin\DeliveryBoy::listCashCollection');

$routes->get('/admin/delivery_boy', 'Admin\DeliveryBoy::index');
$routes->get('/admin/delivery_boy/view', 'Admin\DeliveryBoy::view');
$routes->post('/admin/delivery_boy/add', 'Admin\DeliveryBoy::add');
$routes->post('/admin/delivery_boy/list', 'Admin\DeliveryBoy::list');
$routes->post('/admin/delivery_boy/update', 'Admin\DeliveryBoy::update');
$routes->get('/admin/delivery_boy/edit/(:any)', 'Admin\DeliveryBoy::edit/$1');
$routes->post('/admin/delivery_boy/delete', 'Admin\DeliveryBoy::delete');

$routes->get('/admin/faq', 'Admin\Faq::index');
$routes->post('/admin/faq/list', 'Admin\Faq::list');
$routes->post('/admin/faq/add', 'Admin\Faq::add');
$routes->post('/admin/faq/update', 'Admin\Faq::update');
$routes->get('/admin/faq/edit/(:any)', 'Admin\Faq::edit/$1');
$routes->post('/admin/faq/delete', 'Admin\Faq::delete');

$routes->get('/admin/highlight', 'Admin\Highlights::index');
$routes->post('/admin/highlight/list', 'Admin\Highlights::list');
$routes->post('/admin/highlight/add', 'Admin\Highlights::add');
$routes->post('/admin/highlight/update', 'Admin\Highlights::update');
$routes->get('/admin/highlight/edit/(:any)', 'Admin\Highlights::edit/$1');
$routes->post('/admin/highlight/delete', 'Admin\Highlights::delete');

$routes->get('/admin/payment', 'Admin\PaymentMethod::index');
$routes->post('/admin/payment/update', 'Admin\PaymentMethod::update');

$routes->get('/admin/sms-gateway', 'Admin\Setting::smsGateway');
$routes->post('/admin/sms-gateway/update', 'Admin\Setting::updateSmsGateway');

$routes->get('/admin/order-report', 'Admin\Report::index');
$routes->post('/admin/order-report/generateOrderInsights', 'Admin\Report::generateOrderInsights');
$routes->post('/admin/order-report/refreshOrderInsights', 'Admin\Report::refresheOrderInsights');



$routes->get('/admin/setting', 'Admin\Setting::index');
$routes->get('/admin/store-setting', 'Admin\Setting::storeIndex');
$routes->post('/admin/setting/updateStoreSetting', 'Admin\Setting::updateStoreSetting');
$routes->post('/admin/setting/countrySetting', 'Admin\Setting::countrySetting');
$routes->post('/admin/setting/updateSetting', 'Admin\Setting::updateSetting');
$routes->post('/admin/setting/mail/test', 'Admin\Setting::mailTest');

$routes->post('/admin/setting/language/list', 'Admin\Setting::languageList');
$routes->post('/admin/setting/language/make-default', 'Admin\Setting::makeDefaultLanguage');
$routes->post('/admin/setting/language/toggle-status', 'Admin\Setting::toggleLanguageStatus');

$routes->get('/admin/customer-app-policy', 'Admin\Setting::customerAppPolicy');
$routes->get('/admin/delivery-app-policy', 'Admin\Setting::deliveryAppPolicy');
$routes->get('/admin/seller-app-policy', 'Admin\Setting::sellerAppPolicy');
$routes->post('/admin/setting/update-customer-policy', 'Admin\Setting::updateCustomerAppPolicy');
$routes->post('/admin/setting/update-delivery-policy', 'Admin\Setting::updateDeliveryAppPolicy');
$routes->post('/admin/setting/update-seller-policy', 'Admin\Setting::updateSellerAppPolicy');
$routes->post('/admin/setting/sys-activate', 'Admin\Setting::sysActivate');



$routes->get('/admin/roles', 'Admin\Roles::index');
$routes->post('/admin/roles/add', 'Admin\Roles::add');
$routes->post('/admin/roles/delete', 'Admin\Roles::delete');

$routes->get('/admin/roles/assign-permission/(:any)', 'Admin\RolePermission::index/$1');
$routes->post('/admin/roles/assign-permission/update', 'Admin\RolePermission::update');


$routes->get('/admin/system-user', 'Admin\SystemUser::index');
$routes->post('/admin/system-user/add', 'Admin\SystemUser::add');
$routes->post('/admin/system-user/list', 'Admin\SystemUser::list');
$routes->get('/admin/system-user/edit/(:any)', 'Admin\SystemUser::edit/$1');
$routes->post('/admin/system-user/update', 'Admin\SystemUser::update');
$routes->post('/admin/system-user/delete', 'Admin\SystemUser::delete');


$routes->get('/admin/change_pass', 'Admin\User::changePass');
$routes->post('/admin/change_pass/update', 'Admin\User::resetPassword');

$routes->post('/admin/tags/get-tags', 'Admin\Tags::getTags');

$routes->get('/admin/stock-management', 'Admin\StockMangement::index');
$routes->post('/admin/stock-management/list', 'Admin\StockMangement::list');
$routes->post('/admin/stock-management/updateStock', 'Admin\StockMangement::update');

$routes->get('/admin/pos', 'Admin\Pos::index');
$routes->get('/admin/pos/getTopProducts', 'Admin\Pos::getTopProducts');
$routes->get('/admin/pos/searchProducts', 'Admin\Pos::searchProducts');
$routes->get('/admin/pos/getProductDetails', 'Admin\Pos::getProductDetails');
$routes->get('/admin/pos/searchCustomer', 'Admin\Pos::searchCustomer');
$routes->post('/admin/pos/saveCartSession', 'Admin\Pos::saveCartSession');
$routes->get('/admin/pos/getCartSession', 'Admin\Pos::getCartSession');
$routes->post('/admin/pos/deleteCartSession', 'Admin\Pos::deleteCartSession');
$routes->post('/admin/pos/placeOrder', 'Admin\Pos::placeOrder');
$routes->get('/admin/pos/getCartSessions', 'Admin\Pos::getCartSessions');
$routes->get('/admin/pos/printInvoice', 'Admin\Pos::printInvoice');
$routes->get('/admin/pos-report', 'Admin\Pos::reportIndex');
$routes->post('/admin/pos-report/list', 'Admin\Pos::reportList');

/////////////////// new delivery api
$routes->post('/api/v1/partner/delivery_login', 'DeliveryAppAPI::login');
$routes->post('/api/v1/partner/getActiveCountry', 'DeliveryAppAPI::getActiveCountry');
$routes->post('/api/v1/partner/updateActiveStatus', 'DeliveryAppAPI::updateActiveStatus');
$routes->post('/api/v1/partner/fetchProfile', 'DeliveryAppAPI::fetchProfile');
$routes->post('/api/v1/partner/deleteAccount', 'DeliveryAppAPI::deleteAccount');
$routes->post('/api/v1/partner/updateProfile', 'DeliveryAppAPI::updateProfile');
$routes->post('/api/v1/partner/fetchOrderStatusList', 'DeliveryAppAPI::fetchOrderStatusList');
$routes->post('/api/v1/partner/fetchOrderList', 'DeliveryAppAPI::fetchOrderList');
$routes->post('/api/v1/partner/fetchDeliverySettings', 'DeliveryAppAPI::fetchDeliverySettings');
$routes->post('/api/v1/partner/calculationStats', 'DeliveryAppAPI::calculationStats');
$routes->post('/api/v1/partner/fetchOrderDetails', 'DeliveryAppAPI::fetchOrderDetails');
$routes->post('/api/v1/partner/placeOrderDelivery', 'DeliveryAppAPI::placeOrderDelivery');
$routes->post('/api/v1/partner/fetchReturnOrderList', 'DeliveryAppAPI::fetchReturnOrderList');
$routes->post('/api/v1/partner/fetchReturnOrderDetails', 'DeliveryAppAPI::fetchReturnOrderDetails');
$routes->post('/api/v1/partner/confirmReturnItem', 'DeliveryAppAPI::confirmReturnItem');
$routes->post('/api/v1/partner/updateDeliveryBoyLocation', 'DeliveryAppAPI::updateDeliveryBoyLocation');
$routes->post('/api/v1/partner/fetchCities', 'DeliveryAppAPI::fetchCities');
$routes->post('/api/v1/partner/registerDeliveryBoy', 'DeliveryAppAPI::registerDeliveryBoy');

$routes->get('/api/v1/partner/fetchPrivacyPolicy', 'DeliveryAppAPI::fetchPrivacyPolicy');
$routes->get('/api/v1/partner/fetchAboutUs', 'DeliveryAppAPI::fetchAboutUs');
$routes->get('/api/v1/partner/fetchContactUs', 'DeliveryAppAPI::fetchContactUs');
$routes->get('/api/v1/partner/fetchTermsAndCondition', 'DeliveryAppAPI::fetchTermsAndCondition');
$routes->post('/api/v1/partner/fetchLanguageList', 'DeliveryAppAPI::fetchLanguageList');


/////////////////// V1.5 Customer api
$routes->post('/api/v1/customer/fetchCustomerSettings', 'CustomerAppAPI::fetchCustomerSettings');
$routes->post('/api/v1/customer/signup', 'CustomerAppAPI::signup');
$routes->post('/api/v1/customer/loginWithMobile', 'CustomerAppAPI::loginWithMobile');

$routes->post('/api/v1/customer/verifySignupOtp', 'CustomerAppAPI::verifySignupOtp');
$routes->post('/api/v1/customer/verifyMobileOtp', 'CustomerAppAPI::verifyMobileOtp');
$routes->post('/api/v1/customer/login', 'CustomerAppAPI::login');
$routes->post('/api/v1/customer/sendForgetPasswordOTP', 'CustomerAppAPI::sendForgetPasswordOTP');
$routes->post('/api/v1/customer/verifyForgetPasswordOTP', 'CustomerAppAPI::verifyForgetPasswordOTP');
$routes->post('/api/v1/customer/updatePassword', 'CustomerAppAPI::updatePassword');
$routes->post('/api/v1/customer/googleSignin', 'CustomerAppAPI::googleSignin');
$routes->post('/api/v1/customer/appleLogin', 'CustomerAppAPI::appleLogin');
$routes->post('/api/v1/customer/fetchDeliverableAreaByLatLong', 'CustomerAppAPI::fetchDeliverableAreaByLatLong');
$routes->post('/api/v1/customer/fetchDeliverableAreaByLatLongByDeliverableAreaId', 'CustomerAppAPI::fetchDeliverableAreaByLatLongByDeliverableAreaId');
$routes->post('/api/v1/customer/getBestSellerCategories', 'CustomerAppAPI::getBestSellerCategories');
$routes->post('/api/v1/customer/fetchAllCategories', 'CustomerAppAPI::fetchAllCategories');
$routes->post('/api/v1/customer/fetchGroupCategories', 'CustomerAppAPI::fetchGroupCategories');
$routes->post('/api/v1/customer/fetchHeaderCategories', 'CustomerAppAPI::fetchHeaderCategories');
$routes->post('/api/v1/customer/fetchSubCategoriesByCategoryId', 'CustomerAppAPI::fetchSubCategoriesByCategoryId');
$routes->post('/api/v1/customer/fetchProductBySubcategoryId', 'CustomerAppAPI::fetchProductBySubcategoryId');
$routes->post('/api/v1/customer/fetchProductDetailsById', 'CustomerAppAPI::fetchProductDetailsById');
$routes->post('/api/v1/customer/fetchSimilarProductsByProductId', 'CustomerAppAPI::fetchSimilarProductsByProductId');
$routes->post('/api/v1/customer/fetchCategoryProductsByProductId', 'CustomerAppAPI::fetchCategoryProductsByProductId');
$routes->post('/api/v1/customer/fetchAllNearbySeller', 'CustomerAppAPI::fetchAllNearbySeller');
$routes->post('/api/v1/customer/fetchAllBrand', 'CustomerAppAPI::fetchAllBrand');
$routes->post('/api/v1/customer/1', 'CustomerAppAPI::fetchDealoftheProducts');
$routes->post('/api/v1/customer/headerBanner', 'CustomerAppAPI::headerBanner');
$routes->post('/api/v1/customer/dealOfTheDayBanner', 'CustomerAppAPI::dealOfTheDayBanner');
$routes->post('/api/v1/customer/homeSectionBanner', 'CustomerAppAPI::homeSectionBanner');
$routes->post('/api/v1/customer/footerBanner', 'CustomerAppAPI::footerBanner');


$routes->post('/api/v1/customer/fetchSerachProducts', 'CustomerAppAPI::fetchSerachProducts');

$routes->post('/api/v1/customer/fetchProductsByBrandId', 'CustomerAppAPI::fetchProductsByBrandId');
$routes->post('/api/v1/customer/fetchProductsBySellerId', 'CustomerAppAPI::fetchProductsBySellerId');
$routes->post('/api/v1/customer/fetchSorting', 'CustomerAppAPI::fetchSorting');
$routes->post('/api/v1/customer/fetchOrderList', 'CustomerAppAPI::fetchOrderList');
$routes->post('/api/v1/customer/fetchOrderDetails', 'CustomerAppAPI::fetchOrderDetails');
$routes->post('/api/v1/customer/fetchAddressList', 'CustomerAppAPI::fetchAddressList');
$routes->post('/api/v1/customer/insertAddress', 'CustomerAppAPI::insertAddress');
$routes->post('/api/v1/customer/deleteAddress', 'CustomerAppAPI::deleteAddress');
$routes->post('/api/v1/customer/activeAddress', 'CustomerAppAPI::activeAddress');
$routes->post('/api/v1/customer/fetchCouponList', 'CustomerAppAPI::fetchCouponList');
$routes->post('/api/v1/customer/fetchProfileDetails', 'CustomerAppAPI::fetchProfileDetails');
$routes->post('/api/v1/customer/updateProfileDetails', 'CustomerAppAPI::updateProfileDetails');
$routes->post('/api/v1/customer/deleteUserAccount', 'CustomerAppAPI::deleteUserAccount');

$routes->post('/api/v1/customer/fetchWalletHistory', 'CustomerAppAPI::fetchWalletHistory');
$routes->post('/api/v1/customer/fetchFAQ', 'CustomerAppAPI::fetchFAQ');
$routes->post('/api/v1/customer/fetchHomeSectionByCityId', 'CustomerAppAPI::fetchHomeSectionByCityId');
$routes->post('/api/v1/customer/fetchHighlightsByCityId', 'CustomerAppAPI::fetchHighlightsByCityId');
$routes->get('/api/v1/customer/fetchPrivacyPolicy', 'CustomerAppAPI::fetchPrivacyPolicy');
$routes->get('/api/v1/customer/fetchAboutUs', 'CustomerAppAPI::fetchAboutUs');
$routes->get('/api/v1/customer/fetchContactUs', 'CustomerAppAPI::fetchContactUs');
$routes->get('/api/v1/customer/fetchTermsAndCondition', 'CustomerAppAPI::fetchTermsAndCondition');
$routes->get('/api/v1/customer/fetchRefundPolicy', 'CustomerAppAPI::fetchRefundPolicy');
$routes->post('/api/v1/customer/fetchProductsByFilters', 'CustomerAppAPI::fetchProductsByFilters');
$routes->post('/api/v1/customer/fetchProductVarientByProductId', 'CustomerAppAPI::fetchProductVarientByProductId');
$routes->post('/api/v1/customer/addToCart', 'CustomerAppAPI::addToCart');
$routes->post('/api/v1/customer/removeFromCart', 'CustomerAppAPI::removeFromCart');
$routes->post('/api/v1/customer/fetchSelectedVarientDetails', 'CustomerAppAPI::fetchSelectedVarientDetails');
$routes->post('/api/v1/customer/fetchCartList', 'CustomerAppAPI::fetchCartList');
$routes->post('/api/v1/customer/fetchCartList', 'CustomerAppAPI::fetchCartList');
$routes->post('/api/v1/customer/fetchDeliveryMethods', 'CustomerAppAPI::fetchDeliveryMethods');
$routes->post('/api/v1/customer/fetchPaymentMethods', 'CustomerAppAPI::fetchPaymentMethods');
$routes->post('/api/v1/customer/sellersCart', 'CustomerAppAPI::sellersCart');
$routes->post('/api/v1/customer/isItemInCart', 'CustomerAppAPI::isItemInCart');
$routes->post('/api/v1/customer/fetchDeliveryDate', 'CustomerAppAPI::fetchDeliveryDate');
$routes->post('/api/v1/customer/fetchDeliveryTimeslot', 'CustomerAppAPI::fetchDeliveryTimeslot');
$routes->post('/api/v1/customer/fetchOrderSummary', 'CustomerAppAPI::fetchOrderSummary');
$routes->post('/api/v1/customer/placeCODOrder', 'CustomerAppAPI::placeCODOrder');
$routes->post('/api/v1/customer/createRazorpayOrder', 'CustomerAppAPI::createRazorpayOrder');
$routes->post('/api/v1/customer/verifyRazorpayPayment', 'CustomerAppAPI::verifyRazorpayPayment');
$routes->post('/api/v1/customer/createPaypalOrder', 'CustomerAppAPI::createPaypalOrder');
$routes->post('/api/v1/customer/capturePaypalOrder', 'CustomerAppAPI::capturePaypalOrder');
$routes->post('/api/v1/customer/createPaystackOrder', 'CustomerAppAPI::createPaystackOrder');
$routes->post('/api/v1/customer/verifyPaystackOrder', 'CustomerAppAPI::verifyPaystackOrder');
$routes->post('/api/v1/customer/createCashFreeOrder', 'CustomerAppAPI::createCashFreeOrder');
$routes->post('/api/v1/customer/confirmCashFreeOrder', 'CustomerAppAPI::confirmCashFreeOrder');
$routes->post('/api/v1/customer/paymentFailedUpdate', 'CustomerAppAPI::paymentFailedUpdate');


$routes->post('/api/v1/customer/cancelOrder', 'CustomerAppAPI::cancelOrder');
$routes->post('/api/v1/customer/downloadInvoice', 'CustomerAppAPI::downloadInvoice');

$routes->post('/api/v1/customer/returningItemRequest', 'CustomerAppAPI::returningItemRequest');
$routes->post('/api/v1/customer/trackingOrder', 'CustomerAppAPI::trackingOrder');
$routes->post('/api/v1/customer/fetchLiveDeliveryTracking', 'CustomerAppAPI::fetchLiveDeliveryTracking');
$routes->post('/api/v1/customer/calculateProxyDeliveryTime', 'CustomerAppAPI::calculateProxyDeliveryTime');
$routes->post('/api/v1/customer/fetchNotificationList', 'CustomerAppAPI::fetchNotificationList');
$routes->post('/api/v1/customer/uploadProfilePic', 'CustomerAppAPI::uploadProfilePic');
$routes->post('/api/v1/customer/fetchLanguageList', 'CustomerAppAPI::fetchLanguageList');


/////////////////// new v1.6 Customer api
$routes->post('/api/v1_6/customer/fetchCustomerSettings', 'CustomerAppAPI_1_6::fetchCustomerSettings');
$routes->get('/api/v1_6/customer/fetchCustomerSettings', 'CustomerAppAPI_1_6::fetchCustomerSettings');
$routes->post('/api/v1_6/customer/signup', 'CustomerAppAPI_1_6::signup');
$routes->post('/api/v1_6/customer/loginWithMobile', 'CustomerAppAPI_1_6::loginWithMobile');

$routes->post('/api/v1_6/customer/verifySignupOtp', 'CustomerAppAPI_1_6::verifySignupOtp');
$routes->post('/api/v1_6/customer/verifyMobileOtp', 'CustomerAppAPI_1_6::verifyMobileOtp');
$routes->post('/api/v1_6/customer/login', 'CustomerAppAPI_1_6::login');
$routes->post('/api/v1_6/customer/sendForgetPasswordOTP', 'CustomerAppAPI_1_6::sendForgetPasswordOTP');
$routes->post('/api/v1_6/customer/verifyForgetPasswordOTP', 'CustomerAppAPI_1_6::verifyForgetPasswordOTP');
$routes->post('/api/v1_6/customer/updatePassword', 'CustomerAppAPI_1_6::updatePassword');
$routes->post('/api/v1_6/customer/googleSignin', 'CustomerAppAPI_1_6::googleSignin');
$routes->post('/api/v1_6/customer/appleLogin', 'CustomerAppAPI_1_6::appleLogin');
$routes->post('/api/v1_6/customer/fetchDeliverableAreaByLatLong', 'CustomerAppAPI_1_6::fetchDeliverableAreaByLatLong');
$routes->post('/api/v1_6/customer/fetchDeliverableAreaByLatLongByDeliverableAreaId', 'CustomerAppAPI_1_6::fetchDeliverableAreaByLatLongByDeliverableAreaId');
$routes->post('/api/v1_6/customer/getBestSellerCategories', 'CustomerAppAPI_1_6::getBestSellerCategories');
$routes->post('/api/v1_6/customer/fetchAllCategories', 'CustomerAppAPI_1_6::fetchAllCategories');
$routes->post('/api/v1_6/customer/fetchGroupCategories', 'CustomerAppAPI_1_6::fetchGroupCategories');
$routes->post('/api/v1_6/customer/fetchHeaderCategories', 'CustomerAppAPI_1_6::fetchHeaderCategories');
$routes->post('/api/v1_6/customer/fetchSubCategoriesByCategoryId', 'CustomerAppAPI_1_6::fetchSubCategoriesByCategoryId');
$routes->post('/api/v1_6/customer/fetchProductBySubcategoryId', 'CustomerAppAPI_1_6::fetchProductBySubcategoryId');
$routes->post('/api/v1_6/customer/fetchProductDetailsById', 'CustomerAppAPI_1_6::fetchProductDetailsById');
$routes->post('/api/v1_6/customer/fetchSimilarProductsByProductId', 'CustomerAppAPI_1_6::fetchSimilarProductsByProductId');
$routes->post('/api/v1_6/customer/fetchCategoryProductsByProductId', 'CustomerAppAPI_1_6::fetchCategoryProductsByProductId');
$routes->post('/api/v1_6/customer/fetchAllNearbySeller', 'CustomerAppAPI_1_6::fetchAllNearbySeller');
$routes->post('/api/v1_6/customer/fetchAllBrand', 'CustomerAppAPI_1_6::fetchAllBrand');
$routes->post('/api/v1_6/customer/fetchDealoftheProducts', 'CustomerAppAPI_1_6::fetchDealoftheProducts');
$routes->post('/api/v1_6/customer/headerBanner', 'CustomerAppAPI_1_6::headerBanner');
$routes->post('/api/v1_6/customer/dealOfTheDayBanner', 'CustomerAppAPI_1_6::dealOfTheDayBanner');
$routes->post('/api/v1_6/customer/homeSectionBanner', 'CustomerAppAPI_1_6::homeSectionBanner');
$routes->post('/api/v1_6/customer/footerBanner', 'CustomerAppAPI_1_6::footerBanner');


$routes->post('/api/v1_6/customer/fetchSerachProducts', 'CustomerAppAPI_1_6::fetchSerachProducts');

$routes->post('/api/v1_6/customer/fetchProductsByBrandId', 'CustomerAppAPI_1_6::fetchProductsByBrandId');
$routes->post('/api/v1_6/customer/fetchProductsBySellerId', 'CustomerAppAPI_1_6::fetchProductsBySellerId');
$routes->post('/api/v1_6/customer/fetchSorting', 'CustomerAppAPI_1_6::fetchSorting');
$routes->post('/api/v1_6/customer/fetchOrderList', 'CustomerAppAPI_1_6::fetchOrderList');
$routes->post('/api/v1_6/customer/fetchRunningOrders', 'CustomerAppAPI_1_6::fetchRunningOrders');
$routes->post('/api/v1_6/customer/fetchPreviousOrders', 'CustomerAppAPI_1_6::fetchPreviousOrders');
$routes->post('/api/v1_6/customer/fetchOrderDetails', 'CustomerAppAPI_1_6::fetchOrderDetails');
$routes->post('/api/v1_6/customer/fetchAddressList', 'CustomerAppAPI_1_6::fetchAddressList');
$routes->post('/api/v1_6/customer/insertAddress', 'CustomerAppAPI_1_6::insertAddress');
$routes->post('/api/v1_6/customer/deleteAddress', 'CustomerAppAPI_1_6::deleteAddress');
$routes->post('/api/v1_6/customer/activeAddress', 'CustomerAppAPI_1_6::activeAddress');
$routes->post('/api/v1_6/customer/fetchActiveAddress', 'CustomerAppAPI_1_6::fetchActiveAddress');
$routes->post('/api/v1_6/customer/fetchCouponList', 'CustomerAppAPI_1_6::fetchCouponList');
$routes->post('/api/v1_6/customer/fetchProfileDetails', 'CustomerAppAPI_1_6::fetchProfileDetails');
$routes->post('/api/v1_6/customer/updateProfileDetails', 'CustomerAppAPI_1_6::updateProfileDetails');
$routes->post('/api/v1_6/customer/deleteUserAccount', 'CustomerAppAPI_1_6::deleteUserAccount');

$routes->post('/api/v1_6/customer/fetchWalletHistory', 'CustomerAppAPI_1_6::fetchWalletHistory');
$routes->post('/api/v1_6/customer/fetchFAQ', 'CustomerAppAPI_1_6::fetchFAQ');
$routes->post('/api/v1_6/customer/fetchHighlightsByCityId', 'CustomerAppAPI_1_6::fetchHighlightsByCityId');
$routes->get('/api/v1_6/customer/fetchPrivacyPolicy', 'CustomerAppAPI_1_6::fetchPrivacyPolicy');
$routes->get('/api/v1_6/customer/fetchAboutUs', 'CustomerAppAPI_1_6::fetchAboutUs');
$routes->get('/api/v1_6/customer/fetchContactUs', 'CustomerAppAPI_1_6::fetchContactUs');
$routes->get('/api/v1_6/customer/fetchTermsAndCondition', 'CustomerAppAPI_1_6::fetchTermsAndCondition');
$routes->get('/api/v1_6/customer/fetchRefundPolicy', 'CustomerAppAPI_1_6::fetchRefundPolicy');
$routes->post('/api/v1_6/customer/fetchProductsByFilters', 'CustomerAppAPI_1_6::fetchProductsByFilters');

$routes->post('/api/v1_6/customer/fetchProductVarientByProductId', 'CustomerAppAPI_1_6::fetchProductVarientByProductId');
$routes->post('/api/v1_6/customer/addToCart', 'CustomerAppAPI_1_6::addToCart');
$routes->post('/api/v1_6/customer/removeFromCart', 'CustomerAppAPI_1_6::removeFromCart');
$routes->post('/api/v1_6/customer/fetchSelectedVarientDetails', 'CustomerAppAPI_1_6::fetchSelectedVarientDetails');
$routes->post('/api/v1_6/customer/fetchCartList', 'CustomerAppAPI_1_6::fetchCartList');
$routes->post('/api/v1_6/customer/fetchCartList', 'CustomerAppAPI_1_6::fetchCartList');
$routes->post('/api/v1_6/customer/fetchDeliveryMethods', 'CustomerAppAPI_1_6::fetchDeliveryMethods');
$routes->post('/api/v1_6/customer/fetchPaymentMethods', 'CustomerAppAPI_1_6::fetchPaymentMethods');
$routes->post('/api/v1_6/customer/sellersCart', 'CustomerAppAPI_1_6::sellersCart');
$routes->post('/api/v1_6/customer/isItemInCart', 'CustomerAppAPI_1_6::isItemInCart');
$routes->post('/api/v1_6/customer/fetchDeliveryDate', 'CustomerAppAPI_1_6::fetchDeliveryDate');
$routes->post('/api/v1_6/customer/fetchDeliveryTimeslot', 'CustomerAppAPI_1_6::fetchDeliveryTimeslot');
$routes->post('/api/v1_6/customer/fetchOrderSummary', 'CustomerAppAPI_1_6::fetchOrderSummary');
$routes->post('/api/v1_6/customer/placeCODOrder', 'CustomerAppAPI_1_6::placeCODOrder');
$routes->post('/api/v1_6/customer/createRazorpayOrder', 'CustomerAppAPI_1_6::createRazorpayOrder');
$routes->post('/api/v1_6/customer/verifyRazorpayPayment', 'CustomerAppAPI_1_6::verifyRazorpayPayment');
$routes->post('/api/v1_6/customer/createPaypalOrder', 'CustomerAppAPI_1_6::createPaypalOrder');
$routes->post('/api/v1_6/customer/capturePaypalOrder', 'CustomerAppAPI_1_6::capturePaypalOrder');
$routes->post('/api/v1_6/customer/createPaystackOrder', 'CustomerAppAPI_1_6::createPaystackOrder');
$routes->post('/api/v1_6/customer/verifyPaystackOrder', 'CustomerAppAPI_1_6::verifyPaystackOrder');
$routes->post('/api/v1_6/customer/createCashFreeOrder', 'CustomerAppAPI_1_6::createCashFreeOrder');
$routes->post('/api/v1_6/customer/confirmCashFreeOrder', 'CustomerAppAPI_1_6::confirmCashFreeOrder');
$routes->post('/api/v1_6/customer/paymentFailedUpdate', 'CustomerAppAPI_1_6::paymentFailedUpdate');


$routes->post('/api/v1_6/customer/cancelOrder', 'CustomerAppAPI_1_6::cancelOrder');
$routes->post('/api/v1_6/customer/downloadInvoice', 'CustomerAppAPI_1_6::downloadInvoice');

$routes->post('/api/v1_6/customer/returningItemRequest', 'CustomerAppAPI_1_6::returningItemRequest');
$routes->post('/api/v1_6/customer/trackingOrder', 'CustomerAppAPI_1_6::trackingOrder');
$routes->post('/api/v1_6/customer/fetchLiveDeliveryTracking', 'CustomerAppAPI_1_6::fetchLiveDeliveryTracking');
$routes->post('/api/v1_6/customer/calculateProxyDeliveryTime', 'CustomerAppAPI_1_6::calculateProxyDeliveryTime');
$routes->post('/api/v1_6/customer/fetchNotificationList', 'CustomerAppAPI_1_6::fetchNotificationList');
$routes->post('/api/v1_6/customer/uploadProfilePic', 'CustomerAppAPI_1_6::uploadProfilePic');
$routes->post('/api/v1_6/customer/fetchLanguageList', 'CustomerAppAPI_1_6::fetchLanguageList');

/////////////////// Home Screen & Section API (v1.8)
$routes->post('/api/v1_6/customer/fetchHomeScreens', 'CustomerAppAPI_1_6::fetchHomeScreens');
$routes->post('/api/v1_6/customer/fetchHomeData', 'CustomerAppAPI_1_6::fetchHomeData');
$routes->post('/api/v1_6/customer/fetchSectionProducts', 'CustomerAppAPI_1_6::fetchSectionProducts');


$routes->post('/api/v1_6/customer/fetchSellerById', 'CustomerAppAPI_1_6::fetchSellerById');
$routes->post('/api/v1_6/customer/submitItemRating', 'CustomerAppAPI_1_6::submitItemRating');


/////////////////// seller v2 api
$routes->post('/api/v2/seller/getActiveCountry', 'DeliveryAppAPI::getActiveCountry');
$routes->post('/api/v2/seller/fetchSellerSettings', 'SellerAppApi::fetchSellerSettings');
$routes->get('/api/v2/seller/fetchContactUs', 'DeliveryAppAPI::fetchContactUs');

$routes->post('/api/v2/seller/login', 'SellerAppApi::login');
$routes->post('/api/v2/seller/sendForgetPasswordOTP', 'SellerAppApi::sendForgetPasswordOTP');
$routes->post('/api/v2/seller/verifyForgetPasswordOTP', 'SellerAppApi::verifyForgetPasswordOTP');
$routes->post('/api/v2/seller/updatePassword', 'SellerAppApi::updatePassword');
$routes->post('/api/v2/seller/registerVendor', 'SellerAppApi::registerVendor');
$routes->post('/api/v2/seller/verifyOTP', 'SellerAppApi::verifyOTP');
$routes->post('/api/v2/seller/registerVendorFinal', 'SellerAppApi::registerVendorFinal');
$routes->post('/api/v2/seller/fetchAllCategories', 'CustomerAppAPI::fetchAllCategories');

$routes->post('/api/v2/seller/dashboard', 'SellerAppApi::index'); // pending
$routes->post('/api/v2/seller/fetchAllCategories', 'SellerAppApi::fetchAllCategories');
$routes->post('/api/v2/seller/fetchAllSubcategories', 'SellerAppApi::fetchAllSubcategories');

$routes->get('/api/v2/seller/product', 'SellerAppApi::index');
$routes->get('/api/v2/seller/product-list', 'SellerAppApi::view');
$routes->post('/api/v2/seller/product/add', 'SellerAppApi::add');
$routes->post('/api/v2/seller/product/list', 'SellerAppApi::list');
$routes->get('/api/v2/seller/product/edit/(:any)', 'SellerAppApi::edit/$1');
$routes->post('/api/v2/seller/product/update', 'SellerAppApi::update');
$routes->post('/api/v2/seller/product/delete', 'SellerAppApi::delete');
$routes->get('/api/v2/seller/product/bulk-import', 'SellerAppApi::bulkImport');
$routes->get('/api/v2/seller/product/bulk-update', 'SellerAppApi::bulkUpdate');
$routes->get('/api/v2/seller/product/bulk-import', 'SellerAppApi::bulkImport');
$routes->post('/api/v2/seller/product/bulk-import/insert', 'SellerAppApi::bulkImportFile');
$routes->post('/api/v2/seller/product/bulk-update/update', 'SellerAppApi::bulkUpdateFile');
$routes->get('/api/v2/seller/product/bulk-update/download', 'SellerAppApi::exporsInCSV');
$routes->get('/api/v2/seller/product_order', 'SellerAppApi::productOrder');
$routes->post('/api/v2/seller/product_order/update', 'SellerAppApi::productOrderUpdate');
$routes->post('/api/v2/seller/product/get_product_by_category', 'SellerAppApi::productByCategory');
$routes->get('/api/v2/seller/product/view/(:any)', 'SellerAppApi::vie/$1');
$routes->post('/api/v2/seller/product/delete-other-image', 'SellerAppApi::deleteOtherImage');
$routes->post('/api/v2/seller/product/generateDescription', 'SellerAppApi::generateDescription');
$routes->post('/api/v2/seller/product/generateSeoContent', 'SellerAppApi::generateSeoContent');
$routes->post('/api/v2/seller/product/generate-qr', 'SellerAppApi::generateQrCode');

$routes->get('/api/v2/seller/taxes', 'SellerAppApi::index');
$routes->post('/api/v2/seller/taxes/list', 'SellerAppApi::list');

$routes->post('/api/v2/seller/notification/token/update', 'SellerAppApi::tokenUpdate');

$routes->get('/api/v2/seller/orders', 'SellerAppApi::index');
$routes->post('/api/v2/seller/orders/list', 'SellerAppApi::list');
$routes->get('/api/v2/seller/orders/view/(:any)', 'SellerAppApi::view/$1');
$routes->post('/api/v2/seller/orders/list/(:any)', 'SellerAppApi::listOrderWithLimit/$1');

$routes->post('/api/v2/seller/orders/assignDeliveryBoy', 'SellerAppApi::assignDeliveryBoy');
$routes->post('/api/v2/seller/orders/getDetailsById', 'SellerAppApi::getDetailsById');
$routes->post('/api/v2/seller/orders/update_status', 'SellerAppApi::updateOrderStatus');
$routes->get('/api/v2/seller/orders/orderDetailsTableById/(:any)', 'SellerAppApi::orderDetailsTable/$1');
$routes->post('/api/v2/seller/orders/download_invoice', 'SellerAppApi::downloadInvoice');
$routes->post('/api/v2/seller/orders/delivery_date/update', 'SellerAppApi::updateDeliveryDate');

$routes->get('/api/v2/seller/stock-management', 'SellerAppApi::index');
$routes->post('/api/v2/seller/stock-management/list', 'SellerAppApi::list');
$routes->post('/api/v2/seller/stock-management/updateStock', 'SellerAppApi::update');

$routes->get('/api/v2/seller/wallet-transaction', 'SellerAppApi\WalletTransaction::transactionListView');
$routes->post('/api/v2/seller/wallet-transaction/list', 'SellerAppApi\WalletTransaction::transactionList');
$routes->get('/api/v2/seller/withdrawal-request', 'SellerAppApi\WalletTransaction::withdrawalRequestView');
$routes->post('/api/v2/seller/withdrawal-request/list', 'SellerAppApi\WalletTransaction::withdrawalRequestList');
$routes->post('/api/v2/seller/withdrawal-request/add-request', 'SellerAppApi\WalletTransaction::addTransaction');

$routes->get('/api/v2/seller/product-selling-report', 'SellerAppApi::productSellingReport');
$routes->post('/api/v2/seller/product-selling-report/list', 'SellerAppApi::productSellingReportlist');

$routes->get('/api/v2/seller/selling-report', 'SellerAppApi::sellingReport');
$routes->post('/api/v2/seller/selling-report/list', 'SellerAppApi::sellingReportList');

$routes->get('/api/v2/seller/return-request', 'SellerAppApi::returnRequest');
$routes->post('/api/v2/seller/return-request/list', 'SellerAppApi::returnRequestList');
$routes->post('/api/v2/seller/return-request/view', 'SellerAppApi::viewReturnRequest');
$routes->post('/api/v2/seller/return-request/update', 'SellerAppApi::updateReturnRequest');
$routes->post('/api/v2/seller/tags/get-tags', 'SellerAppApi::getTags');

$routes->post('/api/v2/seller/fetchProducts', 'SellerAppApi::fetchProducts');
$routes->post('/api/v2/seller/fetchCategories', 'SellerAppApi::fetchCategories');
$routes->post('/api/v2/seller/fetchsubCategoriesByCategoryId', 'SellerAppApi::fetchsubCategoriesByCategoryId');
$routes->post('/api/v2/seller/fetchBrand', 'SellerAppApi::fetchBrand');
$routes->post('/api/v2/seller/fetchTaxes', 'SellerAppApi::fetchTaxes');

$routes->post('/api/v2/seller/addProduct', 'SellerAppApi::addProduct');
$routes->post('/api/v2/seller/deleteProduct', 'SellerAppApi::deleteProduct');
$routes->post('/api/v2/seller/productDetails', 'SellerAppApi::productDetails');
$routes->post('/api/v2/seller/updateProduct', 'SellerAppApi::updateProduct');

$routes->post('/api/v2/seller/sellerDetails', 'SellerAppApi::sellerDetails');
$routes->post('/api/v2/seller/sellerDashboard', 'SellerAppApi::sellerDashboard');

$routes->post('/api/v2/seller/fetchOrderList', 'SellerAppApi::fetchOrderList');
$routes->post('/api/v2/seller/fetchOrderDetails', 'SellerAppApi::fetchOrderDetails');
$routes->post('/api/v2/seller/fetchOrderStatusList', 'SellerAppApi::fetchOrderStatusList');

$routes->post('/api/v2/seller/deleteAccount', 'SellerAppApi::deleteAccount');
$routes->post('/api/v2/seller/fetchProfile', 'SellerAppApi::fetchProfile');
$routes->post('/api/v2/seller/updateProfile', 'SellerAppApi::updateProfile');
$routes->post('/api/v2/seller/updateProfilePic', 'SellerAppApi::updateProfilePic');
$routes->post('/api/v2/seller/fetchEarningData', 'SellerAppApi::fetchEarningData');
$routes->post('/api/v2/seller/fetchWeeklyEarningChart', 'SellerAppApi::fetchWeeklyEarningChart');
$routes->post('/api/v2/seller/bulkImportProducts', 'SellerAppApi::bulkImportProducts');
$routes->post('/api/v2/seller/fetchProductSellingReport', 'SellerAppApi::fetchProductSellingReport');
$routes->post('/api/v2/seller/fetchSellingReport', 'SellerAppApi::fetchSellingReport');

$routes->get('/api/v2/seller/fetchAboutUs', 'SellerAppApi::fetchAboutUs');
$routes->get('/api/v2/seller/fetchPrivacyPolicy', 'SellerAppApi::fetchPrivacyPolicy');
$routes->get('/api/v2/seller/fetchTermsAndConditions', 'SellerAppApi::fetchTermsAndConditions');
$routes->post('/api/v2/seller/createTicket', 'SellerAppApi::createTicket');
$routes->post('/api/v2/seller/fetchSellerTickets', 'SellerAppApi::fetchSellerTickets');
$routes->post('/api/v2/seller/fetchTicketDetails', 'SellerAppApi::fetchTicketDetails');

$routes->get('/api/v1_6/customer/image-proxy', 'CustomerAppAPI_1_6::imageProxy');

