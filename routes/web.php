<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/admin', 'AdminController@login');
Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Index Page
Route::get('/', 'IndexController@index');

// Category Listing Pages
Route::get('/products/{url}', 'ProductsController@products');

// Products Filter Page
Route::match(['get', 'post'], '/products/filter', 'ProductsController@filter');

// Main Category Listing Pages
Route::get('/products/main/{url}', 'ProductsController@mainProducts');

// Product Details Page
Route::get('product/{id}', 'ProductsController@product');

// Get Product Attribute Price
Route::get('/get-product-price', 'ProductsController@getProductPrice');

// Update Product Quantity in Cart
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductsController@updateCartQuantity');

// Add To Cart Route
Route::match(['get', 'post'], '/add-cart', 'ProductsController@addtocart');

// Cart Page
Route::match(['get', 'post'], '/cart', 'ProductsController@cart');

// Delete Product from Cart page
Route::get('/cart/delete-product/{id}', 'ProductsController@deleteCartProduct');

// Apply Coupon
Route::post('/cart/apply-coupon', 'ProductsController@applyCoupon');

// Users Register/Login Page
Route::get('/login-register', 'UsersController@userLoginRegister');

// User forgot password recover
Route::match(['get', 'post'], 'forgot-password', 'UsersController@forgotPassword');

// Admin forgot password change
Route::match(['get', 'post'], 'admin-forgot-password', 'AdminController@adminForgotPassword');

// Users Register Form Submit
Route::post('/user-register', 'UsersController@register');

// Confirm Account
Route::get('confirm/{code}', 'UsersController@confirmAccount');

// User Login Form Submit
Route::post('/user-login', 'UsersController@login');

// Users Logout
Route::get('/user-logout', 'UsersController@logout');

// Search Products
Route::get('/search-products', 'ProductsController@searchProducts');

// Route::match(['get', 'post'], '/login-register', 'UsersController@register');
// Check if Email is already exists
Route::match(['get', 'post'], '/check-email', 'UsersController@checkEmail');

// Check pincode
Route::post('/check-pincode', 'ProductsController@checkPincode');

// Check Subscriber Email
Route::post('/check-subscriber-email', 'NewsletterController@checkSubscriber');

// Add Subscriber Email
Route::post('/add-subscriber-email', 'NewsletterController@addSubscriber');

// All Routes after Login
Route::group(['middleware' => 'frontlogin'], function () {
    // User's Account Page
    Route::match(['get', 'post'], '/account', 'UsersController@account');
    // Check User Current Password
    Route::post('/check-user-pwd', 'UsersController@chkUserPassword');
    // Update User Password
    Route::post('/update-user-pwd', 'UsersController@updatePassword');
    // Checkout Page
    Route::match(['get', 'post'], '/checkout', 'ProductsController@checkout');
    // Order Review Page
    Route::match(['get', 'post'], '/order-review', 'ProductsController@orderReview');
    // Place Order
    Route::match(['get', 'post'], '/place-order', 'ProductsController@placeOrder');
    // Thanks Page
    Route::get('/thanks', 'ProductsController@thanks');
    // Paypal Page
    Route::get('/paypal', 'ProductsController@paypal');
    // Users Order Page
    Route::get('/orders', 'ProductsController@userOrders');
    // Users Ordered Products Page
    Route::get('/orders/{id}', 'ProductsController@userOrderDetails');
    // Paypal Thanks Page
    Route::get('/paypal/thanks', 'ProductsController@thanksPaypal');
    // Paypal Cancel Page
    Route::get('/paypal/cancel', 'ProductsController@cancelPaypal');
    // Wish List Page
    Route::match(['get', 'post'], '/wish-list', 'ProductsController@wishList');
    // Delete Product From Wish List 
    Route::get('/wish-list/delete-product/{id}', 'ProductsController@deleteWishListProduct');
});

// Set Middleware , Without Authentication no one can get into this url.......
Route::group(['middleware' => ['adminlogin']], function () {
    Route::get('/admin/dashboard', 'AdminController@dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');


    // Categories Route (ADMIN)
    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory');
    Route::match(['get', 'post'], '/admin/edit-category/{id}', 'CategoryController@editCategory');
    Route::match(['get', 'post'], '/admin/delete-category/{id}', 'CategoryController@deleteCategory');
    Route::get('/admin/view-category', 'CategoryController@viewCategories');

    // Products Route (ADMIN)
    Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@addProduct');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductsController@editProduct');
    Route::get('/admin/view-products', 'ProductsController@viewProducts');
    Route::get('/admin/export-products', 'ProductsController@exportProducts');
    Route::get('/admin/delete-product/{id}', 'ProductsController@deleteProduct');
    Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage');
    Route::get('/admin/delete-product-video/{id}', 'ProductsController@deleteProductVideo');
    Route::get('/admin/delete-alt-image/{id}', 'ProductsController@deleteAltImage');

    // Product Attribute Route
    Route::match(['get','post'], 'admin/add-attributes/{id}', 'ProductsController@addAttributes');
    Route::match(['get','post'], 'admin/edit-attributes/{id}', 'ProductsController@editAttributes');
    Route::match(['get','post'], 'admin/add-images/{id}', 'ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute');

    // Coupon Routes
    Route::match(['get', 'post'], '/admin/add-coupon', 'CouponsController@addCoupon');
    Route::match(['get', 'post'], '/admin/edit-coupon/{id}', 'CouponsController@editCoupon');
    Route::get('/admin/view-coupons', 'CouponsController@viewCoupons');
    Route::get('/admin/delete-coupon/{id}', 'CouponsController@deleteCoupon');

    // Admin Banner Routes
    Route::match(['get', 'post'], '/admin/add-banner', 'BannersController@addBanner');
    Route::match(['get', 'post'], '/admin/edit-banner/{id}', 'BannersController@editBanner');
    Route::get('/admin/view-banners', 'BannersController@viewBanners');
    Route::get('/admin/delete-banner/{id}', 'BannersController@deleteBanner');

    // Admin Orders Routes
    Route::get('/admin/view-orders', 'ProductsController@viewOrders');
    // Admin Orders Charts Routes
    Route::get('/admin/view-orders-charts', 'ProductsController@viewOrdersCharts');
    // Admin Order Details Route
    Route::get('/admin/view-order/{id}', 'ProductsController@viewOrderDetails');
    // Admin Order Invoice Details Route
    Route::get('/admin/view-order-invoice/{id}', 'ProductsController@viewOrderInvoice');
    // Admin PDF Invoice Details Route
    Route::get('/admin/view-pdf-invoice/{id}', 'ProductsController@viewPDFInvoice');
    // Update Order Status
    Route::post('/admin/update-order-status', 'ProductsController@updateOrderStatus');
    // Admin User Route
    Route::get('/admin/view-users', 'UsersController@viewUsers');
    // Admin Users Charts Route
    Route::get('/admin/view-users-charts', 'UsersController@viewUsersCharts');
    // Admin Users Charts Route
    Route::get('/admin/view-users-countries-charts', 'UsersController@viewUsersCountriesCharts');
    // Export Admin User Route
    Route::get('/admin/export-users', 'UsersController@exportUsers');
    // Admin/Sub-Admins View Route
    Route::get('/admin/view-admins', 'AdminController@viewAdmins');
    // Add Admin/Sub-Admins Route
    Route::match(['get', 'post'], '/admin/add-admins', 'AdminController@addAdmin');
    // Edit Admin/Sub-Admins Route
    Route::match(['get', 'post'], '/admin/edit-admin/{id}', 'AdminController@editAdmin');
    // Add CMS Route
    Route::match(['get', 'post'], '/admin/add-cms-page', 'CmsController@addCmsPage');
    // Edit CMS Route
    Route::match(['get', 'post'], '/admin/edit-cms-page/{id}', 'CmsController@editCmsPage');
    // View CMS Route
    Route::match(['get', 'post'], '/admin/view-cms-pages', 'CmsController@viewCmsPage');
    // Delete CMS Page
    Route::get('/admin/delete-cms-page/{id}', 'CmsController@deleteCmsPage');

    // Get Enquiries
    Route::get('/admin/get-enquiries', 'CmsController@getEnquiries');

    // View Enquiries
    Route::get('/admin/view-enquiries', 'CmsController@viewEnquiries');

    // Currencies Route (Add Currency Route)
    Route::match(['get', 'post'], 'admin/add-currency', 'CurrencyController@addCurrency');

    // Currencies Route (Edit Currency Route)
    Route::match(['get', 'post'], 'admin/edit-currency/{id}', 'CurrencyController@editCurrency');

    // Delete Currencies Route
    Route::get('/admin/delete-currency/{id}', 'CurrencyController@deleteCurrency');

    // View Currencies Route
    Route::get('/admin/view-currencies', 'CurrencyController@viewCurrencies');

    // View Shipping Charges
    Route::get('/admin/view-shipping', 'ShippingController@viewShipping');

    // Update Shipping Charges
    Route::match(['get', 'post'], '/admin/edit-shipping/{id}', 'ShippingController@editShipping');

    // View Newsletter Subscribers
    Route::get('/admin/view-newsletter-subscribers', 'NewsletterController@viewNewsletterSubcribers');

    // Update Newsletter Status
    Route::get('/admin/update-newsletter-status/{id}/{status}', 'NewsletterController@updateNewsletterStatus');

    // Delete Newsletter Email
    Route::get('/admin/delete-newsletter-email/{id}', 'NewsletterController@deleteNewsletterEmail');

    // Export Newsletter Emails
    Route::get('/admin/export-newsletter-emails', 'NewsletterController@exportNewsletterEmails');


});

Route::get('/logout', 'AdminController@logout');

// Display Contact Us Page
Route::match(['get', 'post'], '/page/contact', 'CmsController@contact');
// Display CMS Page
Route::match(['get', 'post'], '/page/{url}', 'CmsController@cmsPage');
