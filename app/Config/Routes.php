<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('payment-finish', 'Home::paymentfinish');
$routes->cli('cron/auto-checkout', 'Cron::autoCheckout');
$routes->cli('cron/backup-db', 'Cron::backupDatabase');

/* =========================
 * AUTH
 * ========================= */
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

/* =========================
 * ADMIN AREA
 * ========================= */
$routes->group('admin', ['filter' => 'auth'], function($routes) {

    /* ---------- DASHBOARD ---------- */
    $routes->get('dashboard', 'Admin\Dashboard::index');

    /* ---------- KITCHEN ---------- */
    $routes->get('kitchen', 'Admin\Kitchen::index', ['filter' => 'role:admin,kitchen']);

    /* =========================
     * USERS MANAGEMENT
     * ========================= */
    $routes->get('users', 'Admin\Users::index', ['filter' => 'role:admin']);

    // Datatable
    $routes->post('users/datatable', 'Admin\Users::datatable', ['filter' => 'role:admin']);

    // CRUD
    $routes->get('users/create', 'Admin\Users::create', ['filter' => 'role:admin']);
    $routes->post('users/store', 'Admin\Users::store', ['filter' => 'role:admin']);
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1', ['filter' => 'role:admin']);
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1', ['filter' => 'role:admin']);

    // Delete (AJAX)
    $routes->post('users/delete', 'Admin\Users::deleteAjax', ['filter' => 'role:admin']);

    /* =========================
     * GUESTS MANAGEMENT
     * ========================= */
    $routes->get('guests', 'Admin\Guests::index', ['filter' => 'role:admin,fo,hk,it']);

    // Datatable
    $routes->post('guests/datatable', 'Admin\Guests::datatable', ['filter' => 'role:admin,fo,hk,it']);
    
    $routes->get('guests/room-devices', 'Admin\Guests::roomDevices', ['filter' => 'role:admin,fo,hk,it']);
    $routes->post('guests/checkout', 'Admin\Guests::checkout', ['filter' => 'role:admin,fo,hk,it']);

    // CRUD AJAX
    $routes->post('guests/store', 'Admin\Guests::store', ['filter' => 'role:admin,fo,hk,it']);
    $routes->get('guests/edit/(:num)', 'Admin\Guests::edit/$1', ['filter' => 'role:admin,fo,hk,it']);
    $routes->post('guests/update', 'Admin\Guests::update', ['filter' => 'role:admin,fo,hk,it']);

    // Soft delete & restore
    $routes->post('guests/delete', 'Admin\Guests::deleteAjax', ['filter' => 'role:admin,fo,hk,it']);
    $routes->post('guests/restore', 'Admin\Guests::restore', ['filter' => 'role:admin,fo,hk,it']);

    /* =========================
     * ORDERS
     * ========================= */
    $routes->get('orders', 'Admin\Orders::index', ['filter' => 'role:admin,fnb']);
    $routes->post('orders/datatable', 'Admin\Orders::datatable', ['filter' => 'role:admin,fnb']);
    $routes->get('orders/create', 'Admin\Orders::create', ['filter' => 'role:admin,fnb']);
    $routes->post('orders/store', 'Admin\Orders::store', ['filter' => 'role:admin,fnb']);
    $routes->get('orders/(:num)', 'Admin\Orders::show/$1', ['filter' => 'role:admin,fnb']);
    $routes->post('orders/status/(:num)', 'Admin\Orders::updateStatus/$1', ['filter' => 'role:admin,fnb']);
    $routes->post('orders/update-payment', 'Admin\Orders::updatePayment', ['filter' => 'role:admin,fnb']);
    $routes->get('orders/print/(:num)', 'Admin\Orders::print/$1', ['filter' => 'role:admin,fnb']);
    $routes->get('orders/detail/(:num)', 'Admin\Orders::detail/$1', ['filter' => 'role:admin,fnb']);
    
    /* =========================
     * PAYMENT
     * ========================= */
    $routes->get('payment/midtrans-link/(:num)', 'Admin\Payment::midtransLink/$1');
    $routes->post('midtrans/webhook', 'MidtransWebhook::index');
    $routes->post('midtrans/webhook', 'Admin\Payment::webhook');
    $routes->get('payment/finish', 'Admin\Payment::finish');

    /* =========================
     * MENUS
     * ========================= */
    $routes->get('menus', 'Admin\Menus::index', ['filter' => 'role:admin,fnb']);
    // Datatable
    $routes->post('menus/datatable', 'Admin\Menus::datatable');
    $routes->get('menus/create', 'Admin\Menus::create');
    $routes->post('menus/store', 'Admin\Menus::store');
    $routes->get('menus/show/(:num)', 'Admin\Menus::show/$1');
    $routes->post('menus/update/(:num)', 'Admin\Menus::update/$1');
    $routes->delete('menus/delete/(:num)', 'Admin\Menus::delete/$1');
    $routes->get('menus/variants/(:num)', 'Admin\Menus::variants/$1');

    /* =========================
     * MENU CATEGORIES
     * ========================= */
    $routes->get('menu-categories', 'Admin\MenuCategories::index', ['filter' => 'role:admin,fnb']);
    // Datatable
    $routes->post('menu-categories/datatable', 'Admin\MenuCategories::datatable', ['filter' => 'role:admin,fnb']);
    $routes->get('menu-categories/last-sort-order', 'Admin\MenuCategories::lastSortOrder', ['filter' => 'role:admin,fnb']);
    $routes->post('menu-categories/store', 'Admin\MenuCategories::store', ['filter' => 'role:admin,fnb']);
    $routes->get('menu-categories/show/(:num)', 'Admin\MenuCategories::show/$1', ['filter' => 'role:admin,fnb']);
    $routes->post('menu-categories/update/(:num)', 'Admin\MenuCategories::update/$1', ['filter' => 'role:admin,fnb']);
    $routes->delete('menu-categories/delete/(:num)', 'Admin\MenuCategories::delete/$1', ['filter' => 'role:admin,fnb']);
    $routes->post('menu-categories/sort', 'Admin\MenuCategories::sort', ['filter' => 'role:admin,fnb']);
    $routes->get('menu-categories/sort', 'Admin\MenuCategories::sortView', ['filter' => 'role:admin,fnb']);

    /* =========================
     * MENU VARIANT
     * ========================= */
    $routes->get('menu-variants', 'Admin\MenuVariants::index', ['filter' => 'role:admin,fnb']);
    // Datatable
    $routes->post('menu-variants/datatable', 'Admin\MenuVariants::datatable', ['filter' => 'role:admin,fnb']);
    $routes->post('menu-variants/store', 'Admin\MenuVariants::store', ['filter' => 'role:admin,fnb']);
    $routes->get('menu-variants/(:num)', 'Admin\MenuVariants::show/$1', ['filter' => 'role:admin,fnb']);
    $routes->get('menu-variants/show/(:num)', 'Admin\MenuVariants::show/$1', ['filter' => 'role:admin,fnb']);
    $routes->post('menu-variants/update/(:num)', 'Admin\MenuVariants::update/$1', ['filter' => 'role:admin,fnb']);
    $routes->post('menu-variants/delete/(:num)', 'Admin\MenuVariants::delete/$1', ['filter' => 'role:admin,fnb']);
    
    /* =========================
     * TV MANAGEMENT
     * ========================= */
    $routes->get('tv-devices', 'Admin\TvDevices::index', ['filter' => 'role:admin,eng,it']);
    $routes->post('tv-devices/datatable', 'Admin\TvDevices::datatable', ['filter' => 'role:admin,eng,it']);
    $routes->post('tv-devices/save', 'Admin\TvDevices::save', ['filter' => 'role:admin,eng,it']);
    $routes->delete('tv-devices/delete/(:num)', 'Admin\TvDevices::delete/$1', ['filter' => 'role:admin,eng,it']);

    /* =========================
     * HK ITEMS
     * ========================= */
    $routes->get('hkitems', 'Admin\Hkitems::index', ['filter' => 'role:admin,hk']);
    // Datatable
    $routes->post('hkitems/datatable', 'Admin\Hkitems::datatable', ['filter' => 'role:admin,hk']);
    $routes->get('hkitems/create', 'Admin\Hkitems::create', ['filter' => 'role:admin,hk']);
    $routes->post('hkitems/store', 'Admin\Hkitems::store', ['filter' => 'role:admin,hk']);
    $routes->get('hkitems/show/(:num)', 'Admin\Hkitems::show/$1', ['filter' => 'role:admin,hk']);
    $routes->post('hkitems/update/(:num)', 'Admin\Hkitems::update/$1', ['filter' => 'role:admin,hk']);
    $routes->post('hkitems/delete/(:num)', 'Admin\Hkitems::delete/$1', ['filter' => 'role:admin,hk']);

    /* =========================
     * HK CATEGORIES
     * ========================= */
    $routes->get('hk-categories', 'Admin\HkCategories::index', ['filter' => 'role:admin,hk']);
    $routes->post('hk-categories/datatable', 'Admin\HkCategories::datatable', ['filter' => 'role:admin,hk']);
    $routes->post('hk-categories/store', 'Admin\HkCategories::store', ['filter' => 'role:admin,hk']);
    $routes->post('hk-categories/update/(:num)', 'Admin\HkCategories::update/$1', ['filter' => 'role:admin,hk']);
    $routes->post('hk-categories/delete/(:num)', 'Admin\HkCategories::delete/$1', ['filter' => 'role:admin,hk']);
    $routes->post('hk-categories/sort', 'Admin\HkCategories::sort', ['filter' => 'role:admin,hk']);
    $routes->get('hk-categories/sort', 'Admin\HkCategories::sortView', ['filter' => 'role:admin,hk']);

    /* =========================
     * HK ORDERS
     * ========================= */
    $routes->get('hkorders', 'Admin\Hkorders::index', ['filter' => 'role:admin,hk']);
    $routes->post('hkorders/datatable', 'Admin\Hkorders::datatable', ['filter' => 'role:admin,hk']);
    $routes->get('hkorders/(:num)', 'Admin\Hkorders::show/$1', ['filter' => 'role:admin,hk']);
    $routes->post('hkorders/status/(:num)', 'Admin\Hkorders::updateStatus/$1', ['filter' => 'role:admin,hk']);
    $routes->get('hkorders/detail/(:num)', 'Admin\Hkorders::detail/$1', ['filter' => 'role:admin,hk']);
});

/* =========================
 * API AREA (PUBLIC)
 * ========================= */
$routes->group('api', function ($routes) {

    /* =====================
     * GUEST / DEVICE
     * ===================== */
    $routes->get('guest/device/(:segment)', 'Api\GuestApi::byDevice/$1');
    $routes->get('guest/room/(:segment)', 'Api\GuestApi::byRoom/$1');

    /* =====================
     * MENU & CATEGORY
     * ===================== */
    $routes->get('categories', 'Api\CategoryApi::index'); 
    // hanya kategori aktif
    
    $routes->get('menus', 'Api\MenuApi::index'); 
    // semua menu aktif

    $routes->get('menus/category/(:num)', 'Api\MenuApi::byCategory/$1'); 
    // menu per kategori

    $routes->get('menus/(:num)', 'Api\MenuApi::show/$1'); 
    // detail menu + variants
    
    $routes->get('menus/roomservice', 'Api\MenuApi::roomService');
    // Room Service

    $routes->get('menus/roomservice/category/(:num)', 'Api\MenuApi::roomServiceByCategory/$1');
    // Room Service by Category

    /* =====================
     * VARIANTS
     * ===================== */
    $routes->get('menus/(:num)/variants', 'Api\MenuVariantApi::byMenu/$1');

    /* =====================
     * ORDERS
     * ===================== */
    $routes->post('orders', 'Api\OrderApi::store');
    $routes->get('orders/(:segment)', 'Api\OrderApi::show/$1');
    $routes->get('orders/room/(:segment)', 'Api\OrderApi::byRoom/$1');
    
    /* =====================
     * PAYMENT
     * ===================== */
    $routes->post('payment/payment-notification', 'Api\Payment::notification');

    /* =====================
     * IPTV EXTRA (OPTIONAL)
     * ===================== */
    $routes->get('banners', 'Api\BannerApi::index');
    $routes->get('promos', 'Api\PromoApi::index');


    /* =====================
     * HK MENU & CATEGORY
     * ===================== */
    $routes->get('hk/categories', 'Api\CategoryApi::houseKeeping'); 
    // hanya kategori aktif

    $routes->get('hk/menus/roomservice/category/(:num)', 'Api\MenuApi::houseKeepingByCategory/$1');
    // Room Service by Category
});
