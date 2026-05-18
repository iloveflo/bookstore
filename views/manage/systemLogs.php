<?php
use App\SessionGuard as Guard;
use App\Models\User;

// Bảo vệ trang: Yêu cầu quyền xem log
if (!Guard::can('log.view')) {
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
    <meta name="description" content="Nhật ký hệ thống - BookStore Admin">
    <title>Nhật Ký Hệ Thống | Admin Bookworm Store</title>
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
                            <a class="nav-link active" href="systemLogs">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-history text-info me-2"></i>Nhật Ký Thao Tác Hệ Thống</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-outline-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                            <i class="fas fa-trash-alt me-1"></i> Xóa Sạch Nhật Ký
                        </button>
                    </div>
                </div>

                <?php if (isset($messages['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-1"></i> <?= $this->e($messages['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Tìm kiếm & Lọc -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body bg-light">
                        <form method="GET" action="systemLogs" class="row g-3">
                            <div class="col-md-8 col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm hành động, tài khoản, chi tiết..." value="<?= $this->e($_GET['search'] ?? '') ?>">
                                    <button class="btn btn-primary px-4" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2">
                                <?php if (!empty($_GET['search'])): ?>
                                    <a href="systemLogs" class="btn btn-outline-secondary w-100"><i class="fas fa-undo me-1"></i>Reset</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bảng Logs -->
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;" class="text-center">ID</th>
                                    <th style="width: 180px;">Thời Gian</th>
                                    <th style="width: 250px;">Người Thực Hiện</th>
                                    <th style="width: 180px;" class="text-center">Hành Động</th>
                                    <th>Chi Tiết Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($logs->isEmpty()): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Không tìm thấy nhật ký thao tác nào.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
                                        <?php 
                                            // Lựa chọn màu Badge dựa trên hành động
                                            $badgeColor = 'bg-secondary';
                                            $action = $log->action;
                                            if (strpos($action, 'Xóa') !== false || strpos($action, 'hủy') !== false || strpos($action, 'thất bại') !== false || strpos($action, 'Tắt') !== false) {
                                                $badgeColor = 'bg-danger';
                                            } elseif (strpos($action, 'Tạo') !== false || strpos($action, 'Thêm') !== false || strpos($action, 'thành công') !== false || strpos($action, 'Bật') !== false) {
                                                $badgeColor = 'bg-success';
                                            } elseif (strpos($action, 'Cập nhật') !== false || strpos($action, 'Sửa') !== false) {
                                                $badgeColor = 'bg-warning text-dark';
                                            } elseif (strpos($action, 'Đăng nhập') !== false || strpos($action, 'Đăng xuất') !== false) {
                                                $badgeColor = 'bg-primary';
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-center text-muted fw-bold"><?= $log->id ?></td>
                                            <td><small class="text-muted"><i class="far fa-clock me-1"></i><?= date('d/m/Y H:i:s', strtotime($log->created_at)) ?></small></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tag text-muted me-2"></i>
                                                    <span class="fw-semibold text-dark"><?= $this->e($log->user_name) ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center" style="width: 180px; vertical-align: middle;">
                                                <span class="badge <?= $badgeColor ?> px-3 py-2 shadow-sm rounded-pill" style="font-size: 0.8rem; letter-spacing: 0.3px; position: relative; top: -3px; display: inline-block;">
                                                    <?= $this->e($log->action) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (empty($log->details)): ?>
                                                    <span class="text-muted italic">Không có chi tiết</span>
                                                <?php else: ?>
                                                    <?php 
                                                        $isJson = false;
                                                        $details = $log->details;
                                                        // Kiểm tra nếu là json string để làm đẹp khi click xem
                                                        if (is_string($details) && is_array(json_decode($details, true))) {
                                                            $isJson = true;
                                                        }
                                                    ?>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="text-truncate d-inline-block" style="max-width: 500px;" title="<?= $this->e($details) ?>">
                                                            <?= $this->e(mb_substr($details, 0, 100)) ?><?= mb_strlen($details) > 100 ? '...' : '' ?>
                                                        </span>
                                                        <button class="btn btn-sm btn-outline-info rounded px-3 py-1 btn-view-detail" 
                                                                data-id="<?= $log->id ?>" 
                                                                data-action="<?= $this->e($log->action) ?>" 
                                                                data-user="<?= $this->e($log->user_name) ?>" 
                                                                data-time="<?= date('d/m/Y H:i:s', strtotime($log->created_at)) ?>"
                                                                data-json="<?= $isJson ? 'true' : 'false' ?>"
                                                                data-details="<?= $this->e($details) ?>">
                                                            <i class="fas fa-eye me-1"></i> Xem
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Phân trang -->
                <?php if ($pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4 mb-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="systemLogs?page=<?= $page - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo; Trước</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $pages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="systemLogs?page=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $pages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="systemLogs?page=<?= $page + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" aria-label="Next">
                                    <span aria-hidden="true">Sau &raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Modal Chi Tiết Log (Premium Redesigned Layout) -->
    <div class="modal fade" id="logDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e293b, #0f172a); padding: 20px 24px;">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center m-0" style="font-size: 1.25rem;">
                        <i class="fas fa-info-circle me-2 text-info" style="font-size: 1.4rem;"></i>Chi Tiết Hành Động
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(1) brightness(2); opacity: 0.8;"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <div class="row g-3 mb-4">
                        <!-- Người thực hiện block -->
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border border-light d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; min-width: 45px;">
                                    <i class="fas fa-user-shield fs-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <span class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.8px;">Người thực hiện</span>
                                    <strong class="text-dark fs-6" id="modal-log-user"></strong>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thời gian block -->
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border border-light d-flex align-items-center">
                                <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; min-width: 45px;">
                                    <i class="far fa-clock fs-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <span class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.8px;">Thời gian thao tác</span>
                                    <strong class="text-dark fs-6" id="modal-log-time"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Hành động block -->
                        <div class="col-12">
                            <div class="p-3 bg-white rounded-3 shadow-sm border border-light d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center overflow-hidden">
                                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; min-width: 45px;">
                                        <i class="fas fa-tasks fs-5"></i>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-3">
                                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.8px; margin-bottom: 0;">Hành động hệ thống:</span>
                                        <span class="badge bg-primary px-3 py-2 shadow-sm rounded-pill" id="modal-log-action" style="font-size: 0.85rem; font-weight: 600; letter-spacing: 0.3px; position: relative !important; display: inline-block !important; left: auto !important; top: auto !important; z-index: 1 !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payload / Chi tiết block -->
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="card-header bg-dark d-flex align-items-center justify-content-between px-3 py-2" style="background-color: #1e293b !important; border-bottom: 1px solid #334155;">
                            <span class="text-white small fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;"><i class="fas fa-code me-2 text-warning"></i>NỘI DUNG CHI TIẾT (PAYLOAD)</span>
                            <button class="btn btn-sm btn-outline-light py-1 px-3 rounded-2 fw-semibold d-flex align-items-center" style="font-size: 0.7rem; transition: all 0.2s;" onclick="copyPayloadToClipboard()">
                                <i class="far fa-copy me-1.5"></i> Copy Payload
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-3 bg-dark text-success-light" style="max-height: 350px; overflow-y: auto; font-family: 'Courier New', Courier, monospace; font-size: 0.85rem; line-height: 1.6; color: #a3e635 !important; background-color: #0f172a !important;" id="modal-log-payload"></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3" style="background-color: #f1f5f9;">
                    <button type="button" class="btn btn-secondary px-4 py-2 fw-semibold rounded-3 shadow-sm d-flex align-items-center" data-bs-dismiss="modal" style="background-color: #64748b; border: none; font-size: 0.9rem; transition: all 0.2s;">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xóa Sạch Logs -->
    <div class="modal fade" id="clearLogsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Xác Nhận Xóa Sạch Nhật Ký</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Bạn có chắc chắn muốn <strong>xóa toàn bộ lịch sử nhật ký thao tác</strong> hệ thống không? Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="clearLogs" method="POST">
                        <button type="submit" class="btn btn-danger px-4">Xác nhận xóa sạch</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hàm copy payload lên clipboard tiện lợi
        function copyPayloadToClipboard() {
            var payloadText = $('#modal-log-payload').text();
            navigator.clipboard.writeText(payloadText).then(function() {
                alert('Đã sao chép nội dung chi tiết vào bộ nhớ tạm!');
            }, function(err) {
                console.error('Không thể sao chép: ', err);
            });
        }

        $(document).ready(function() {
            $('.btn-view-detail').on('click', function() {
                var user = $(this).data('user');
                var time = $(this).data('time');
                var action = $(this).data('action');
                var details = $(this).data('details');
                var isJson = $(this).data('json');

                $('#modal-log-user').text(user);
                $('#modal-log-time').text(time);
                $('#modal-log-action').text(action);

                if (isJson) {
                    try {
                        // Thử format json cho đẹp mắt
                        var obj = JSON.parse(details);
                        var formatted = JSON.stringify(obj, null, 4);
                        $('#modal-log-payload').text(formatted);
                    } catch (e) {
                        $('#modal-log-payload').text(details);
                    }
                } else {
                    $('#modal-log-payload').text(details);
                }

                $('#logDetailModal').modal('show');
            });
        });
    </script>
</body>

</html>
