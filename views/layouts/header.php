<link href="/css/search.css" rel="stylesheet">
<link href="/css/manage.css" rel="stylesheet">
<?php $activePage = basename(rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')); ?>
<!-- Topbar Start -->
<div class="container-fluid bg-primary py-3 d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center pt-2">
                    <a class="text-white pr-3" href="">FAQs</a>
                    <span class="text-white">|</span>
                    <a class="text-white px-3" href="">Help</a>
                    <span class="text-white">|</span>
                    <a class="text-white pl-3" href="">Support</a>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <form method="GET" accept-charset="UTF-8" class="search-box" action="search" name="search-form">
                        <button class="btn-search" type="submit" value="Search"><i class="fas fa-search"></i></button>
                        <input type="search" class="input-search" placeholder=" Tìm kiếm... " name="search" value="">
                    </form>
                    <a class="text-white px-3" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-white px-3" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-white pl-3" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid position-relative nav-bar p-0">
    <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9;">
        <nav class="navbar sticky-top navbar-expand-lg bg-white navbar-light shadow p-lg-0">
            <a href="index.html" class="navbar-brand d-block d-lg-none">
                <h1 class="m-0 display-4 text-primary"><span class="text-secondary">BOOK</span>worm</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto py-0">
                    <a href="home" class="nav-item nav-link <?= ($activePage == 'home') ? 'active' : ''; ?>">Trang chủ</a>
                    <a href="about" class="nav-item nav-link <?= ($activePage == 'about') ? 'active' : ''; ?>">Giới thiệu</a>
                    <a href="product_all" class="nav-item nav-link <?= ($activePage == 'product_all') ? 'active' : ''; ?>">Sản phẩm</a>
                    <a href="blog" class="nav-item nav-link <?= ($activePage == 'blog') ? 'active' : ''; ?>">Blog</a>
                </div>
                <a href="home" class="navbar-brand mx-5 d-none d-lg-block">
                    <h2 class="m-0 display-4 text-secondary"><span class="text-primary">BOOK</span>worm</h2>
                </a>
                <div class="navbar-nav mr-auto py-0">
                    <?php if (!\App\SessionGuard::isUserLoggedIn()) : ?>
                        <a href="login" class="nav-item nav-link <?= ($activePage == 'login' or $activePage == 'register') ? 'active' : ''; ?>">👤Tài khoản</a>
                    <?php else : ?>
                        <?php if (\App\SessionGuard::isAdminLoggedIn()) : ?>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle nav-item nav-link" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    Quản Lý<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="ps-4 mb-2 text-start">
                                        <a href="manageProduct">Sản Phẩm</a>
                                    </li>
                                    <li class="ps-4 mb-2 text-start">
                                        <a href="users">Người Dùng</a>
                                    </li>
                                    <li class="ps-4 mb-2 text-start">
                                        <a href="manageBill">Đơn Hàng</a>
                                    </li>
                                    <li class="ps-4 mb-2 text-start">
                                        <a href="manageArticles">Bài Viết</a>
                                    </li>
                                    <li class="ps-4 mb-2 text-start">
                                        <a href="dashboard">Dashboard</a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif ?>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle nav-item nav-link" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span>👤</i> </span><?= $this->e(\App\SessionGuard::user()->name) ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li class="px-2  mb-2 text-start">
                                    <a href="payHistory">Lịch sử mua hàng</a>
                                </li>
                                <li class="px-2 mb-2 text-start">
                                    <a href="userInfo?userId=<?= $this->e(\App\SessionGuard::user()->id) ?>">Thông tin tài khoản</a>
                                </li>
                                <li class="px-2 text-start">
                                    <a href="logout" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Đăng xuất ➡️
                                    </a>
                                    <form id="logout-form" action="logout" method="POST" style="display: none;">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php endif ?>
                    <div>
                        <a href="cart" class="nav-item nav-link <?= ($activePage == 'cart') ? 'active' : ''; ?>">🛒 Giỏ hàng</a>
                        <?php
                        if (isset($_SESSION['subtotal'])) {
                            echo ' <span class="badgecart">' . $_SESSION['subtotal'] . '</span>';
                        } ?>

                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

</header>