<?php
use App\SessionGuard as Guard;
use App\Models\User;

// Bảo vệ trang: Yêu cầu quyền xem người dùng (toàn bộ hoặc chỉ khách hàng)
if (!Guard::can('user.view_all') && !Guard::can('user.view_customers')) {
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
    <meta name="description" content="Quản lý người dùng - BookStore Admin">
    <title>Quản lý Người Dùng | Admin Bookworm Store</title>
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

        .navbar-brand h5 {
            letter-spacing: 1px;
        }

        /* Rest of existing styles */
        .b-example-divider { height: 3rem; background-color: rgba(0, 0, 0, .1); border: solid rgba(0, 0, 0, .15); border-width: 1px 0; box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15); }
        .b-example-vr { flex-shrink: 0; width: 1.5rem; height: 100vh; }
        .bi { vertical-align: -.125em; fill: currentColor; }
        .nav-scroller { position: relative; z-index: 2; height: 2.75rem; overflow-y: hidden; }
        .nav-scroller .nav { display: flex; flex-wrap: nowrap; padding-bottom: 1rem; margin-top: -1px; overflow-x: auto; text-align: center; white-space: nowrap; -webkit-overflow-scrolling: touch; }
    </style>


    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <style>
        /* CSS cho danh sách sản phẩm trong lịch sử đơn hàng */
        .product-details-list {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .product-item {
            display: flex;
            align-items: flex-start; /* Căn lề trên để hỗ trợ text nhiều dòng */
            padding: 10px 0;
            border-bottom: 1px solid #f1f1f1;
            line-height: 1.5; /* Tránh xén chữ (Text Clipping) */
            transition: background 0.2s;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-name-box {
            flex-grow: 1;
            padding-right: 20px;
            color: #2c3e50;
            font-size: 0.95rem;
            word-break: break-word; /* Đảm bảo không vỡ khung */
        }
        .product-qty-box {
            flex-shrink: 0;
            white-space: nowrap;
        }
        .product-qty-badge {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
            padding: 4px 8px;
            font-size: 0.8rem;
            border-radius: 4px;
            font-weight: 600;
        }
        
        /* Cố định layout bảng lịch sử để không bị tràn */
        #history-table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
        }
        #history-table th, #history-table td {
            overflow: hidden;
            word-wrap: break-word;
            position: relative; /* Fix "Bẫy Position" - làm điểm neo cho các thành phần bên trong */
            padding: 12px 8px;
        }
        
        #history-table .badge {
            display: inline-block;
            width: auto; /* Để badge có độ dài tự nhiên */
            min-width: 110px; /* Đảm bảo các badge có độ dài tương đối đều nhau nhưng không quá dài */
            white-space: nowrap; 
            text-align: center;
            padding: 6px 12px; /* Thu hẹp độ cao lại một chút */
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Đồng bộ căn giữa bảng lịch sử */
        #orderHistoryModal table td {
            vertical-align: middle !important;
        }
    </style>
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
                            <a class="nav-link active" href="users">
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
                            <a class="nav-link" href="systemConfig">
                                <i class="fas fa-cogs"></i> Thao tác hệ thống
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="mb-4 d-flex flex-row justify-content-between pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <?php if ($currentUser->can('user.create')): ?>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="fa fa-plus"></i> Thêm Nhân Sự
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <form class="text-right" role="search" action="users" method="POST">
                        <select class="form-select form-select-sm h-100" style="display: inline" name="sort-user" onchange="this.form.submit()">
                            <option value="1" <?= isset($old_user_selected['val']) ? ($old_user_selected['val'] == 1 ? 'selected' : '') : '' ?>>Mặc định</option>
                            <option value="2" <?= isset($old_user_selected['val']) ? ($old_user_selected['val'] == 2 ? 'selected' : '') : '' ?>>ID: Thấp đến Cao</option>
                            <option value="3" <?= isset($old_user_selected['val']) ? ($old_user_selected['val'] == 3 ? 'selected' : '') : '' ?>>ID: Cao đến Thấp</option>
                            <option value="4" <?= isset($old_user_selected['val']) ? ($old_user_selected['val'] == 4 ? 'selected' : '') : '' ?>>Mới nhất</option>
                        </select>
                    </form>
                </div>

                <?php if (isset($messages['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->e($messages['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Lỗi:</strong> 
                        <?php 
                            if (is_array($errors)) {
                                echo implode('; ', $errors);
                            } else {
                                echo $errors;
                            }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <table id="all-users" class="table table-bordered table-responsive mb-5" style="border-color: #cacaca!important;">
                    <thead class="bg-info text-light text-center">
                        <tr>
                            <th scope="col" class="text-uppercase">ID</th>
                            <th scope="col" class="text-uppercase">Tên Người Dùng</th>
                            <th scope="col" class="text-uppercase">Email</th>
                            <th scope="col" class="text-uppercase">SĐT</th>
                            <th scope="col" class="text-uppercase" style="min-width: 200px;">Vai Trò</th>
                            <th scope="col" class="text-uppercase">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_manage as $user_manage) : ?>
                            <tr>
                                <td style="text-align: center; vertical-align: middle;"><?= $this->e($user_manage->id) ?></td>
                                <td style="vertical-align: middle;"><?= $this->e($user_manage->name) ?></td>
                                <td style="vertical-align: middle;"><?= $this->e($user_manage->email) ?></td>
                                <td style="vertical-align: middle;"><?= $this->e($user_manage->phone) ?></td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <?php 
                                            $uObj = new User();
                                            $uObj->role = $user_manage->role;
                                            echo '<span class="badge bg-secondary shadow-sm" style="min-width: 100px; padding: 6px 10px;">' . $this->e($uObj->getRoleLabel()) . '</span>';
                                        ?>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <?php if ($user_manage->id === $currentUser->id): ?>
                                            <span class="badge bg-info text-dark shadow-sm" style="padding: 6px 12px;"><i class="fas fa-user-check me-1"></i> Bạn</span>
                                        <?php elseif ($user_manage->role === User::ROLE_CUSTOMER): ?>
                                            <button class="btn btn-sm btn-outline-info view-history" 
                                                    data-id="<?= $this->e($user_manage->id) ?>" 
                                                    data-name="<?= $this->e($user_manage->name) ?>"
                                                    title="Xem lịch sử mua hàng">
                                                <i class="fas fa-history"></i> Lịch sử
                                            </button>
                                        <?php else: ?>
                                            <?php if ($currentUser->isAdmin() || ($currentUser->hasRole(\App\Models\User::ROLE_STORE_OWNER) && $user_manage->role === \App\Models\User::ROLE_ORDER_STAFF)): ?>
                                                <button class="btn btn-sm btn-outline-warning button-edit" 
                                                        data-id="<?= $this->e($user_manage->id) ?>" 
                                                        data-name="<?= $this->e($user_manage->name) ?>"
                                                        data-email="<?= $this->e($user_manage->email) ?>"
                                                        data-phone="<?= $this->e($user_manage->phone) ?>"
                                                        data-address="<?= $this->e($user_manage->address) ?>"
                                                        data-role="<?= $this->e($user_manage->role) ?>"
                                                        title="Xem chi tiết & Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger button-delete" data-id="<?= $this->e($user_manage->id) ?>" data-name="<?= $this->e($user_manage->name) ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            </main>
        </div>
    </div>

    <!-- Modal Thêm Nhân Sự -->
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addStaffModalLabel"><i class="fas fa-user-plus"></i> Thêm Nhân Sự Mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="createStaff" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Nhập họ và tên">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="example@gmail.com">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Tối thiểu 6 ký tự">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Xác nhận lại">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="0xxxxxxxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Quyền hạn <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="<?= User::ROLE_ORDER_STAFF ?>">Nhân viên bán hàng</option>
                                    <?php if ($currentUser->isAdmin()): ?>
                                    <option value="<?= User::ROLE_STORE_OWNER ?>">Chủ cửa hàng</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Nhập địa chỉ tạm trú"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4">Xác nhận thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết & Sửa Nhân Sự -->
    <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editStaffModalLabel"><i class="fas fa-user-edit"></i> Chi tiết & Cập nhật nhân sự</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="updateStaff" method="POST">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email (Không thể sửa)</label>
                            <input type="text" class="form-control bg-light" id="edit-email" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại (Không thể sửa)</label>
                            <input type="text" class="form-control bg-light" id="edit-phone" readonly disabled>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Tên nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-role" class="form-label">Quyền hạn <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-role" name="role" required>
                                <option value="<?= User::ROLE_ORDER_STAFF ?>">Nhân viên bán hàng</option>
                                <?php if ($currentUser->isAdmin()): ?>
                                <option value="<?= User::ROLE_STORE_OWNER ?>">Chủ cửa hàng</option>
                                <?php endif; ?>
                                <option value="<?= User::ROLE_ADMIN ?>" style="display:none;" disabled>Quản trị viên</option>
                                <option value="<?= User::ROLE_STORE_OWNER ?>" style="display:none;" disabled>Chủ cửa hàng</option>
                            </select>
                            <div class="form-text text-info small"><i class="fas fa-info-circle"></i> Không thể nâng cấp nhân sự lên mức quyền cao hơn giới hạn của bạn.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="edit-address" name="address" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-warning px-4">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="delete-confirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận xóa</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa người dùng <span id="user-info-delete" class="fw-bold"></span> không?
                </div>
                <div class="modal-footer">
                    <form id="delete-form" action="deleteUser" method="POST">
                        <input type="hidden" name="id" id="delete-user-id">
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History Modal -->
    <div class="modal fade" id="orderHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i>Lịch sử đơn hàng: <span id="history-user-name"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="order-history-content" class="table-responsive">
                        <table class="table table-hover align-middle" id="history-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Mã HĐ</th>
                                    <th style="width: 110px;">Tổng tiền</th>
                                    <th style="width: 130px;">Ngày lập</th>
                                    <th style="width: 300px;">Sản phẩm</th>
                                    <th style="width: 140px;">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody id="order-list-body">
                                <!-- Ajax content here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="history-loading" class="text-center py-4 d-none">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Đang tải lịch sử...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Xử lý Modal Xóa
            $('.button-delete').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                $('#user-info-delete').text(name + " (ID: " + id + ")");
                $('#delete-user-id').val(id);
                $('#delete-confirm').modal('show');
            });

            // Xử lý Modal Sửa (Xem chi tiết)
            $('.button-edit').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var phone = $(this).data('phone');
                var address = $(this).data('address');
                var role = $(this).data('role');

                $('#edit-id').val(id);
                $('#edit-name').val(name);
                $('#edit-email').val(email);
                $('#edit-phone').val(phone);
                $('#edit-address').val(address);
                
                // Hiển thị role hiện tại của user vào select (kể cả option ẩn)
                var editRoleSelect = $('#edit-role');
                editRoleSelect.find('option[value="' + role + '"]').prop('selected', true);
                
                // Vô hiệu hóa việc đổi role nếu đang sửa Admin, hoặc nếu là Store Owner tự sửa Store Owner
                var isCurrentUserAdmin = <?= $currentUser->isAdmin() ? 'true' : 'false' ?>;
                if (role === '<?= User::ROLE_ADMIN ?>' || (role === '<?= User::ROLE_STORE_OWNER ?>' && !isCurrentUserAdmin)) {
                    editRoleSelect.prop('disabled', true);
                } else {
                    editRoleSelect.prop('disabled', false);
                }

                $('#editStaffModal').modal('show');
            });

            // Xử lý Modal Lịch sử đơn hàng
            $('.view-history').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                $('#history-user-name').text(name);
                $('#order-list-body').empty();
                $('#history-loading').removeClass('d-none');
                $('#order-history-content').addClass('d-none');
                $('#orderHistoryModal').modal('show');

                $.ajax({
                    url: 'getUserOrders',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        $('#history-loading').addClass('d-none');
                        $('#order-history-content').removeClass('d-none');
                        
                        if (response.success && response.orders.length > 0) {
                            var html = '';
                            response.orders.forEach(function(order) {
                                var statusText = '';
                                var statusBadgeClass = '';
                                
                                switch(order.trang_thai) {
                                    case 'processing': 
                                        statusText = 'Đang chuẩn bị'; 
                                        statusBadgeClass = 'bg-info text-white'; 
                                        break;
                                    case 'sending': 
                                        statusText = 'Đang vận chuyển'; 
                                        statusBadgeClass = 'bg-warning text-dark'; 
                                        break;
                                    case 'recieved': 
                                        statusText = 'Hoàn tất'; 
                                        statusBadgeClass = 'bg-success text-white'; 
                                        break;
                                    case 'Canceled': 
                                        statusText = 'Đã hủy'; 
                                        statusBadgeClass = 'bg-secondary text-white'; 
                                        break;
                                    default: 
                                        statusText = order.trang_thai;
                                        statusBadgeClass = 'bg-light text-dark border';
                                }
                                
                                // Format tiền VND
                                var amount = new Intl.NumberFormat('vi-VN').format(order.tong_tien) + 'đ';
                                
                                // Tạo danh sách sản phẩm (Cột riêng, đã fix CSS)
                                var detailsHtml = '<div class="product-details-list">';
                                if (order.details && order.details.length > 0) {
                                    order.details.forEach(function(item) {
                                        detailsHtml += '<div class="product-item">' +
                                            '<div class="product-name-box">' +
                                                '<i class="fas fa-book-open me-2 text-primary opacity-50"></i>' + item.ten_sach + 
                                            '</div>' +
                                            '<div class="product-qty-box">' +
                                                '<span class="product-qty-badge">x' + item.so_luong_sp + '</span>' +
                                            '</div>' +
                                        '</div>';
                                    });
                                } else {
                                    detailsHtml = '<span class="text-muted italic">Không có dữ liệu chi tiết</span>';
                                }
                                detailsHtml += '</div>';

                                html += '<tr>' +
                                    '<td class="fw-bold">#' + order.ma_hoa_don + '</td>' +
                                    '<td class="text-primary fw-bold">' + amount + '</td>' +
                                    '<td><small>' + order.ngay_lap + '</small></td>' +
                                    '<td>' + detailsHtml + '</td>' +
                                    '<td><span class="badge ' + statusBadgeClass + ' shadow-sm border-0">' + statusText + '</span></td>' +
                                '</tr>';
                            });
                            $('#order-list-body').html(html);
                        } else {
                            $('#order-list-body').html('<tr><td colspan="4" class="text-center py-4 text-muted">Người dùng này chưa có đơn hàng nào.</td></tr>');
                        }
                    },
                    error: function() {
                        $('#history-loading').addClass('d-none');
                        $('#order-list-body').html('<tr><td colspan="4" class="text-center py-4 text-danger">Không thể tải lịch sử đơn hàng. Vui lòng thử lại.</td></tr>');
                    }
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>