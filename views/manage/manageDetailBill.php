<?php
use App\SessionGuard as Guard;
$currentUser = Guard::user();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Admin Bookworms Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="/img/favicon.jpg" rel="shortcut icon">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        /* Premium Header Styling */
        .admin-header {
            background-color: #1a1d20 !important;
            border-bottom: 1px solid #343a40;
        }
        
        .user-profile-box {
            display: flex;
            align-items: center;
            padding: 5px 20px;
            border-left: 1px solid #343a40;
            margin-right: 10px;
        }

        .user-info-text {
            display: flex;
            flex-direction: column;
            margin-left: 12px;
            line-height: 1.2;
        }

        .user-name {
            color: #fff;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #0dcaf0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .btn-logout {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b !important;
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 6px;
            padding: 6px 15px !important;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-right: 15px;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: #fff !important;
        }

        .user-avatar {
            display: flex;
            align-items: center;
        }

        .navbar-brand h5 {
            letter-spacing: 1px;
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top admin-header flex-md-nowrap p-0 shadow-sm">
        <a href="home" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 ">
            <h5 class="m-0 display-4 fs-5 text-secondary fw-bold"><span class="text-primary fs-5 fw-bold">BOOK</span>worm</h5>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="d-flex align-items-center ms-auto">
            <div class="user-profile-box d-none d-md-flex">
                <div class="user-avatar">
                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                </div>
                <div class="user-info-text">
                    <span class="user-name"><?= $this->e($currentUser->name) ?></span>
                    <span class="user-role"><?= $this->e($currentUser->getRoleLabel()) ?></span>
                </div>
            </div>
            
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link btn-logout px-3" href="logout" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="logout" method="POST" style="display: none;">
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="home">
                                <i class="fas fa-home"></i>
                                Trang Chủ
                            </a>
                        </li>
                        <?php if ($currentUser->can('product.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageProduct">
                                <i class="fa fa-book"></i>
                                Sản Phẩm
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('bill.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="manageBill">
                                <i class="fa fa-shopping-cart"></i>
                                Đơn Hàng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('user.view_all') || $currentUser->can('user.view_customers')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="users">
                                <i class="fas fa-user-friends"></i>
                                Người Dùng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('article.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageArticles">
                                <i class="fas fa-newspaper"></i>
                                Bài Viết
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('dashboard.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard">
                                <i class="fas fa-chart-line"></i>
                                Dashboard
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between pt-3">
                    <a href="manageBill" class="btn btn-primary">
                        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Trở về
                    </a>
                </div>
                <div class="row table-product mt-4">
                    <table class="table text-center">
                        <thead class="bg-info text-light">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">SẢN PHẨM</th>
                                <th scope="col"></th>
                                <th scope="col">SỐ LƯỢNG</th>
                                <th scope="col">THÀNH TIỀN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($bill)) {
                                foreach ($bill as $index => $item) {
                                    echo '<tr class="align-middle">
                        <th scope="row">' . $index + 1 . '</th>
                        <td class="col-3"> <a href="/detail?masp=' . $item->ma_san_pham . '""><img src="/img/product/' . $item->hinh_anh . '" width="50%" ></a></td>
                        <td><p class="text-dark text-start">' . $item['ten_sach'] . '</p> <p class="text-dark text-start fw-bold">' . number_format($item->gia_khuyen_mai, 0, '.', ',') . 'đ</p></td>
                        <td>' . $item['so_luong_sp'] . '</td>
                        <td>' . number_format($item['gia_khuyen_mai'] * $item['so_luong_sp'], 0, '.', ',')  . 'đ</td>   
                        </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                if (isset($bill)) {
                    $k = 0;
                    foreach ($billdetail as $item) {
                        while ($item['ma_hoa_don'] != $k) {
                            echo '<div class="row bg-info bg-opacity-10 rounded mb-5"><div class="col text-start mt-4">
                        <p><b>Trạng thái đơn hàng: </b>' ; if ($item['trang_thai'] == "Canceled")  {echo 'Đã hủy';} 
                        else if ($item['trang_thai'] == "processing")  {echo 'Đang chuẩn bị hàng';}
                        else if ($item['trang_thai'] == "sending")  {echo 'Đang vận chuyển';} 
                        else if ($item['trang_thai'] == "recieved")  {echo 'Đơn hàng đã hoàn tất';} echo '</p>
                        <p><b>Trạng thái thanh toán: </b>' . $item['trang_thai_thanh_toan'] . '</p>
                        <p><b>Ngày đặt hàng: </b>' . $item['ngay_lap'] . '</p>
                        </div>
                        <div class="col text-end mt-4">
                        <p><b>Địa chỉ nhận hàng:</b></p>
                        <p>' . $item['ten_khach_hang'] . ' ' . $item['sdt'] . '</p>
                        <p>' . $item['dia_chi'] . '</p></div>
                        </div>';
                            $k = $item['ma_hoa_don'];
                        }
                    }
                }
                ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="/dashboard.js"></script>
</body>

</html>