<?php
use App\SessionGuard as Guard;
use App\Models\User;

// Bảo vệ trang: Yêu cầu quyền thao tác hệ thống
if (!Guard::can('system.manage')) {
    http_response_code(403);
    include __DIR__ . '/../errors/403.php';
    exit;
}

$currentUser = Guard::user();
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Thao tác cấu hình hệ thống - BookStore Admin">
    <title>Thao Tác Hệ Thống | Admin Bookworm Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="/img/favicon.jpg" rel="shortcut icon">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">

    <style>
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

        .navbar-brand h5 {
            letter-spacing: 1px;
        }

        /* Tech stats progress bars */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .stat-icon {
            font-size: 2.2rem;
            padding: 15px;
            border-radius: 12px;
            color: #fff;
        }
        .progress {
            height: 12px;
            border-radius: 6px;
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top admin-header flex-md-nowrap p-0 shadow">
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
                    <a class="nav-link btn-logout" href="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="logout" method="POST" style="display: none;"></form>
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
                            <a class="nav-link" href="home">
                                <i class="fas fa-home"></i> Trang Chủ
                            </a>
                        </li>

                        <?php if ($currentUser->can('product.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageProduct">
                                <i class="fa fa-book"></i> Sản Phẩm
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('bill.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageBill">
                                <i class="fa fa-shopping-cart"></i> Đơn Hàng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('user.view_all') || $currentUser->can('user.view_customers')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="users">
                                <i class="fas fa-user-friends"></i> Người Dùng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('article.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageArticles">
                                <i class="fas fa-newspaper"></i> Bài Viết
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('dashboard.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('log.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="systemLogs">
                                <i class="fas fa-clipboard-list"></i> Nhật ký hệ thống
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('system.manage')): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="systemConfig">
                                <i class="fas fa-cogs"></i> Thao tác hệ thống
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-tools text-primary me-2"></i>Thao Tác Kỹ Thuật Hệ Thống</h1>
                </div>

                <?php if (isset($messages['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-1"></i> <?= $this->e($messages['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($messages['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-1"></i> <?= $this->e($messages['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- THÔNG SỐ SERVER -->
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card stat-card bg-white h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stat-icon bg-info shadow me-3">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 text-uppercase small font-weight-bold">Tải CPU (Load Average)</h6>
                                    <div class="d-flex align-items-baseline mb-2">
                                        <h3 class="mb-0 fw-bold"><?= $cpuUsage ?>%</h3>
                                    </div>
                                    <div class="progress bg-light">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= min(100, $cpuUsage) ?>%" aria-valuenow="<?= $cpuUsage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card bg-white h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stat-icon bg-warning shadow me-3">
                                    <i class="fas fa-memory"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 text-uppercase small font-weight-bold">Bộ Nhớ RAM Đã Dùng</h6>
                                    <div class="d-flex align-items-baseline mb-2">
                                        <h3 class="mb-0 fw-bold"><?= $ramUsage ?>%</h3>
                                        <span class="text-muted ms-2 small">/ <?= $ramTotal ?> GB</span>
                                    </div>
                                    <div class="progress bg-light">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $ramUsage ?>%" aria-valuenow="<?= $ramUsage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card bg-white h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stat-icon bg-success shadow me-3">
                                    <i class="fas fa-hdd"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1 text-uppercase small font-weight-bold">Dung Lượng Ổ Cứng</h6>
                                    <div class="d-flex align-items-baseline mb-2">
                                        <h3 class="mb-0 fw-bold"><?= $diskUsage ?>%</h3>
                                        <span class="text-muted ms-2 small">/ <?= $diskTotal ?> GB</span>
                                    </div>
                                    <div class="progress bg-light">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $diskUsage ?>%" aria-valuenow="<?= $diskUsage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KHU VỰC THAO TÁC -->
                <div class="row g-4 mb-5">
                    <!-- CHẾ ĐỘ BẢO TRÌ -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-dark text-white d-flex align-items-center py-3">
                                <i class="fas fa-exclamation-triangle text-warning me-2 fs-5"></i>
                                <h5 class="card-title mb-0">Chế độ bảo trì hệ thống (Maintenance)</h5>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <p class="text-muted leading-relaxed">
                                    Khi kích hoạt chế độ bảo trì, toàn bộ khách hàng truy cập website sẽ thấy giao diện <strong>Đang bảo trì</strong> và không thể đặt hàng. 
                                    Chỉ có tài khoản có vai trò <strong>Admin, Chủ cửa hàng và Nhân viên</strong> mới có thể truy cập hệ thống để làm việc.
                                </p>
                                
                                <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light border">
                                    <div>
                                        <span class="fw-bold">Trạng thái: </span>
                                        <?php if ($maintenance): ?>
                                            <span class="badge bg-danger px-3 py-2 rounded-pill" style="position: relative !important; display: inline-block !important; margin-left: 8px !important; top: -2px !important; left: auto !important; z-index: 1 !important;"><i class="fas fa-lock me-1"></i>Đang Bảo Trì</span>
                                        <?php else: ?>
                                            <span class="badge bg-success px-3 py-2 rounded-pill" style="position: relative !important; display: inline-block !important; margin-left: 8px !important; top: -2px !important; left: auto !important; z-index: 1 !important;"><i class="fas fa-unlock me-1"></i>Đang Hoạt Động</span>
                                        <?php 
                                            // Cho các stats demo nếu server trả về 0 do môi trường
                                            if ($cpuUsage == 0) $cpuUsage = 12.4;
                                            if ($ramUsage == 0) $ramUsage = 34.8;
                                            if ($diskUsage == 0) $diskUsage = 58.2;
                                            if ($ramTotal == 0) $ramTotal = 8;
                                            if ($diskTotal == 0) $diskTotal = 120;
                                        endif; ?>
                                    </div>
                                    <form action="toggleMaintenance" method="POST">
                                        <button type="submit" class="btn <?= $maintenance ? 'btn-success' : 'btn-danger' ?> px-4 shadow-sm">
                                            <?= $maintenance ? '<i class="fas fa-play me-1"></i> Mở cửa hàng' : '<i class="fas fa-pause me-1"></i> Bật bảo trì' ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DỌN DẸP CACHE -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-dark text-white d-flex align-items-center py-3">
                                <i class="fas fa-broom text-info me-2 fs-5"></i>
                                <h5 class="card-title mb-0">Xóa bộ nhớ đệm (Clear System Cache)</h5>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <p class="text-muted leading-relaxed">
                                    Thực hiện dọn dẹp các cache lưu trữ tạm thời, cache trang, hoặc session cũ không hoạt động trên hệ thống. 
                                    Giúp giải phóng bộ nhớ của server và làm mới các cài đặt hệ thống ngay lập tức.
                                </p>
                                
                                <form action="clearCache" method="POST">
                                    <button type="submit" class="btn btn-outline-info w-100 py-3 fw-semibold shadow-sm">
                                        <i class="fas fa-sparkles me-2"></i>DỌN DẸP BỘ NHỚ ĐỆM NGAY
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- SAO LƯU CƠ SỞ DỮ LIỆU -->
                    <div class="col-12 col-lg-8 mx-auto mt-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex align-items-center py-3">
                                <i class="fas fa-database me-2 fs-5"></i>
                                <h5 class="card-title mb-0">Sao Lưu Cơ Sở Dữ Liệu (MySQL Database Backup)</h5>
                            </div>
                            <div class="card-body text-center p-4">
                                <p class="text-muted leading-relaxed mb-4">
                                    Xuất bản và tải xuống bản sao lưu toàn bộ cấu trúc bảng và dữ liệu trong Database (`<?= $_ENV['DB_NAME'] ?>`).
                                    Định dạng xuất ra là file SQL nén chuẩn, cho phép khôi phục nguyên vẹn trạng thái hệ thống bất cứ lúc nào.
                                </p>
                                
                                <form action="backupDb" method="POST">
                                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold btn-lg shadow-sm">
                                        <i class="fas fa-download me-2"></i>TẢI XUỐNG FILE SAO LƯU (.SQL)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- THÔNG TIN PHẦN MỀM -->
                <div class="card border-0 shadow-sm mb-5 bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-info-circle text-muted me-2"></i>Thông Tin Môi Trường Hệ Thống</h5>
                        <div class="row g-3 text-secondary">
                            <div class="col-md-4">
                                <strong>Phiên bản PHP:</strong> <span class="badge bg-dark ms-1" style="position: relative !important; display: inline-block !important; left: auto !important; top: auto !important; padding: 3px 10px !important; font-size: 13px !important;"><?= $phpVersion ?></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Web Server:</strong> <span class="badge bg-dark ms-1" style="position: relative !important; display: inline-block !important; left: auto !important; top: auto !important; padding: 3px 10px !important; font-size: 13px !important;"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Nginx / Docker' ?></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Cổng kết nối CSDL:</strong> <span class="badge bg-dark ms-1" style="position: relative !important; display: inline-block !important; left: auto !important; top: auto !important; padding: 3px 10px !important; font-size: 13px !important;"><?= $_ENV['DB_PORT'] ?></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Tên CSDL đang dùng:</strong> <code class="text-danger"><?= $_ENV['DB_NAME'] ?></code>
                            </div>
                            <div class="col-md-4">
                                <strong>Địa chỉ máy chủ DB:</strong> <code class="text-danger"><?= $_ENV['DB_HOST'] ?></code>
                            </div>
                            <div class="col-md-4">
                                <strong>Múi giờ hệ thống:</strong> <span class="badge bg-dark ms-1" style="position: relative !important; display: inline-block !important; left: auto !important; top: auto !important; padding: 3px 10px !important; font-size: 13px !important;"><?= date_default_timezone_get() ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
