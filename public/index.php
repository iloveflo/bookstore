<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../app/functions.php';

define('APPNAME', 'Bookworms Store');

session_start();
checkRememberedLogin();

$router = new \Bramus\Router\Router();

// Auth routes
$router->post('/logout', '\App\Controllers\Auth\LoginController@logout');
$router->get('/register', '\App\Controllers\Auth\RegisterController@showRegisterForm');
$router->post('/register', '\\App\Controllers\Auth\RegisterController@register');
$router->get('/login', '\App\Controllers\Auth\LoginController@showLoginForm');
$router->post('/login', '\App\Controllers\Auth\LoginController@login');
// Routes cho Quên mật khẩu
$router->get('/forgot-password', '\App\Controllers\Auth\ForgotPasswordController@showForm');
$router->post('/forgot-password', '\App\Controllers\Auth\ForgotPasswordController@sendResetLink');

// Route cho trang Đặt lại mật khẩu (khi bấm vào link trong email)
$router->get('/reset-password', '\App\Controllers\Auth\ForgotPasswordController@showResetForm');
$router->post('/reset-password', '\App\Controllers\Auth\ForgotPasswordController@resetPassword');

// Google
$router->get('/auth/google', '\App\Controllers\Auth\LoginController@loginGoogle');
$router->get('/auth/google/callback', '\App\Controllers\Auth\LoginController@callbackGoogle');

// Facebook
$router->get('/auth/facebook', '\App\Controllers\Auth\LoginController@loginFacebook');
$router->get('/auth/facebook/callback', '\App\Controllers\Auth\LoginController@callbackFacebook');

// Product routes
$router->get('/', '\App\Controllers\HomeController@index');
$router->get('/home', '\App\Controllers\HomeController@index');
$router->get('/about', '\App\Controllers\HomeController@about');
$router->get('/search', '\App\Controllers\HomeController@search');

$router->get('/product_all', '\App\Controllers\ProductController@product');
$router->get('/product', '\App\Controllers\ProductController@productOfType');
$router->get('/detail', '\App\Controllers\ProductController@detailProduct');

$router->get('/cart', '\App\Controllers\CartController@cart');
$router->post('/addCart', '\App\Controllers\CartController@addCart');
$router->post('/del', '\App\Controllers\CartController@del');
$router->get('/delCart', '\App\Controllers\CartController@delCart');
$router->post('/pay', '\App\Controllers\CartController@pay');

// Bill routes
$router->get('/payHistory', '\App\Controllers\BillController@payHistory');
$router->get('/detailBill', '\App\Controllers\BillController@detailBill');
$router->post('/cancelBill/([0-9]+)', '\App\Controllers\BillController@cancel');
$router->post('/recieved/([0-9]+)', '\App\Controllers\BillController@recieved');
$router->post('/received/([A-Za-z0-9]+)', '\App\Controllers\BillController@received');

//Blog
$router->get('/blog', '\App\Controllers\ArticleController@index');
$router->get('/blog/detail/([0-9]+)', '\App\Controllers\ArticleController@show');

// Management Products routes
$router->get('/manageProduct', '\App\Controllers\Manage\ManagementController@getAllProducts');
$router->post('/manageProduct', '\App\Controllers\Manage\ManagementController@sortAllProducts');

$router->get('/create', '\App\Controllers\Manage\ManagementController@showCreatePage');
$router->post('/createProduct', '\App\Controllers\Manage\ManagementController@createProduct');

$router->get('/manage/([A-Za-z0-9]+)', '\App\Controllers\Manage\ManagementController@showUpdatePage');
$router->post('/manage/update/([A-Za-z0-9]+)', '\App\Controllers\Manage\ManagementController@update');
$router->post('/manage/delete/([A-Za-z0-9]+)', '\App\Controllers\Manage\ManagementController@delete');

//Management Bill routes
$router->get('/manageBill', '\App\Controllers\Manage\ManagementController@manageBill');
$router->get('/manageDetailBill', '\App\Controllers\Manage\ManagementController@manageDetailBill');
$router->post('/manage/deleteBill/([0-9]+)', '\App\Controllers\Manage\ManagementController@cancelBill');
$router->post('/manage/sending/([0-9]+)', '\App\Controllers\Manage\ManagementController@send');
$router->post('/manageBill', '\App\Controllers\Manage\ManagementController@sortBill');

// Management Articles routes
$router->get('/manageArticles', '\App\Controllers\Manage\ManagementController@indexArticles');
$router->get('/manageArticles/createArticle', '\App\Controllers\Manage\ManagementController@createArticle');
$router->post('/manageArticles/storeArticle', '\App\Controllers\Manage\ManagementController@storeArticle');
$router->get('/manageArticles/editArticle/([0-9]+)', '\App\Controllers\Manage\ManagementController@editArticle');
$router->post('/manageArticles/updateArticle/([0-9]+)', '\App\Controllers\Manage\ManagementController@updateArticle');
$router->post('/manageArticles/deleteArticle/([0-9]+)', '\App\Controllers\Manage\ManagementController@deleteArticle');

// Router hiển thị Dashboard
$router->get('/dashboard', '\App\Controllers\DashboardController@index');

// Management Users routes
$router->get('/users', '\App\Controllers\Manage\ManagementController@getAllUsers');
$router->post('/users', '\App\Controllers\Manage\ManagementController@sortAllUsers');
$router->get('/userInfo', '\App\Controllers\Manage\ManagementController@userInfo');
$router->post('/updateUser', '\App\Controllers\Manage\ManagementController@updateUser');
$router->get('/passChange', '\App\Controllers\Manage\ManagementController@passChange');
$router->post('/updatePass', '\App\Controllers\Manage\ManagementController@updatePass');

$router->setBasePath('/bookstore/public');
$router->run();

