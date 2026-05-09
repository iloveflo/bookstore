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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 0.9rem;
            box-shadow: 0 0 10px rgba(13, 202, 240, 0.2);
        }

        .user-avatar {
            display: flex;
            align-items: center;
        }

        .navbar-brand h5 {
            letter-spacing: 1px;
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
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
                            <a class="nav-link active" href="manageProduct">
                                <i class="fa fa-book"></i>
                                Sản Phẩm
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('bill.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageBill">
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
                <div class="mb-4 d-flex flex-row justify-content-between pt-3 pb-2 mb-3 border-bottom align-items-center">
                    <div class="d-flex gap-2">
                        <a href="create" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Thêm Sản Phẩm</a>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#metadataSection">
                            <i class="fas fa-tags me-1"></i> Quản lý Thuộc tính
                        </button>
                    </div>
                    <form class="text-right" role="search" action="manageProduct" method="POST">
                        <select class="form-select" style="display: inline; width: auto;" name="sort-price" onchange="this.form.submit()">
                            <option value="1" <?= isset($old_selected['val']) ? ($old_selected['val'] == 1 ? 'selected' : '') : '' ?>>Mặc định</option>
                            <option value="2" <?= isset($old_selected['val']) ? ($old_selected['val'] == 2 ? 'selected' : '') : '' ?>>Giá: Thấp -> Cao</option>
                            <option value="3" <?= isset($old_selected['val']) ? ($old_selected['val'] == 3 ? 'selected' : '') : '' ?>>Giá: Cao -> Thấp</option>
                            <option value="4" <?= isset($old_selected['val']) ? ($old_selected['val'] == 4 ? 'selected' : '') : '' ?>>Bán chạy nhất</option>
                        </select>
                    </form>
                </div>

                <?php // Xóa phần Alert cũ, sẽ dùng SweetAlert2 ?>

                <!-- Metadata Management Section -->
                <div class="collapse mb-4" id="metadataSection">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-types" type="button">Loại Sách</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-authors" type="button">Tác Giả</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-publishers" type="button">Nhà Xuất Bản</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <!-- Types Tab -->
                                <div class="tab-pane fade show active" id="pills-types" role="tabpanel">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold"><i class="fas fa-list me-2"></i>Danh sách Loại Sách</h6>
                                        <button class="btn btn-sm btn-success btn-add-metadata" data-type="type"><i class="fas fa-plus"></i> Thêm mới</button>
                                    </div>
                                    <table class="table table-sm table-white table-hover align-middle border shadow-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Mã Loại</th>
                                                <th>Tên Loại</th>
                                                <th class="text-center">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($types as $type): ?>
                                            <tr>
                                                <td><?= $this->e($type->ma_loai_sach) ?></td>
                                                <td><?= $this->e($type->ten_loai_sach) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-warning btn-edit-metadata" data-type="type" data-id="<?= $type->ma_loai_sach ?>" data-name="<?= $type->ten_loai_sach ?>"><i class="fas fa-edit"></i></button>
                                                    <form action="/bookstore/public/deleteType/<?= $type->ma_loai_sach ?>" method="POST" class="d-inline form-delete-metadata" data-name="<?= $this->e($type->ten_loai_sach) ?>">
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-meta-trigger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Authors Tab -->
                                <div class="tab-pane fade" id="pills-authors" role="tabpanel">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold"><i class="fas fa-pen-nib me-2"></i>Danh sách Tác Giả</h6>
                                        <button class="btn btn-sm btn-success btn-add-metadata" data-type="author"><i class="fas fa-plus"></i> Thêm mới</button>
                                    </div>
                                    <table class="table table-sm table-white table-hover align-middle border shadow-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Mã Tác Giả</th>
                                                <th>Tên Tác Giả</th>
                                                <th class="text-center">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($authors as $author): ?>
                                            <tr>
                                                <td><?= $this->e($author->ma_tac_gia) ?></td>
                                                <td><?= $this->e($author->ten_tac_gia) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-warning btn-edit-metadata" data-type="author" data-id="<?= $author->ma_tac_gia ?>" data-name="<?= $author->ten_tac_gia ?>"><i class="fas fa-edit"></i></button>
                                                    <form action="/bookstore/public/deleteAuthor/<?= $author->ma_tac_gia ?>" method="POST" class="d-inline form-delete-metadata" data-name="<?= $this->e($author->ten_tac_gia) ?>">
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-meta-trigger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Publishers Tab -->
                                <div class="tab-pane fade" id="pills-publishers" role="tabpanel">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold"><i class="fas fa-building me-2"></i>Danh sách Nhà Xuất Bản</h6>
                                        <button class="btn btn-sm btn-success btn-add-metadata" data-type="publisher"><i class="fas fa-plus"></i> Thêm mới</button>
                                    </div>
                                    <table class="table table-sm table-white table-hover align-middle border shadow-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Mã NXB</th>
                                                <th>Tên NXB</th>
                                                <th>SĐT</th>
                                                <th>Địa chỉ</th>
                                                <th class="text-center">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($publishers as $nxb): ?>
                                            <tr>
                                                <td><?= $this->e($nxb->ma_nxb) ?></td>
                                                <td><?= $this->e($nxb->ten_nxb) ?></td>
                                                <td><?= $this->e($nxb->sdt_nxb) ?></td>
                                                <td><?= $this->e($nxb->dia_chi_nxb) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-warning btn-edit-metadata" data-type="publisher" data-id="<?= $nxb->ma_nxb ?>" data-name="<?= $nxb->ten_nxb ?>" data-phone="<?= $nxb->sdt_nxb ?>" data-address="<?= $nxb->dia_chi_nxb ?>"><i class="fas fa-edit"></i></button>
                                                    <form action="/bookstore/public/deletePublisher/<?= $nxb->ma_nxb ?>" method="POST" class="d-inline form-delete-metadata" data-name="<?= $this->e($nxb->ten_nxb) ?>">
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-meta-trigger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table id="all-products" class="table table-bordered table-responsive mb-5" style="border-color: #cacaca!important;">
                    <thead class="bg-info text-light text-uppercase text-center align-middle">
                        <tr>
                            <th>ID</th>
                            <th>Tên Sản Phẩm</th>
                            <th style="width: 100px">Giá Gốc</th>
                            <th style="width: 100px">Giá Khuyến Mãi</th>
                            <th>Hình Ảnh</th>
                            <th>Số Lượng</th>
                            <th>Loại Sản Phẩm</th>
                            <th>Đã Bán</th>
                            <th style="width: 100px">Ngày Tạo</th>
                            <th>Cập Nhật</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products_manage as $product_manage) : ?>
                            <tr style="font-size: 15px;">
                                <input class="" type="hidden" name="id" value="<?= $this->e($product_manage->ma_sach) ?>">
                                <td style="text-align: center; vertical-align: middle;"><?= $this->e($product_manage->ma_sach) ?></td>
                                <td style="vertical-align: middle;"><a class="text-black" href="detail?masp=<?= $product_manage->ma_sach ?>"><?= $this->e($product_manage->ten_sach) ?></a></td>
                                <td style="vertical-align: middle;"><?= $this->e(number_format($product_manage->gia_sach, 0, ',', '.')) ?> VNĐ</td>
                                <td style="vertical-align: middle;"><?= $this->e(number_format($product_manage->gia_khuyen_mai, 0, ',', '.')) ?> VNĐ</td>
                                <td style="text-align: center; vertical-align: middle;"><img src="/img/product/<?= $this->e($product_manage->hinh_anh) ?>" style="width: 90px;"></td>
                                <td style="text-align: center; vertical-align: middle;"><?= $this->e($product_manage->so_luong) ?></td>
                                <td style="text-align: center; vertical-align: middle;"><?= $this->e($product_manage->ten_loai_sach) ?></td>
                                <td style="vertical-align: middle;"><?= $this->e($product_manage->sold) ?></td>
                                <td style="vertical-align: middle;"><?= $this->e($product_manage->created_at) ?></td>
                                <td style="text-align: center; vertical-align: middle;"><a href="manage/<?= $this->e($product_manage->ma_sach) ?>" class="btn btn-xs btn-warning" style="padding: 3px 6px;">
                                        <i alt="Edit" class="fa fa-edit"></i>
                                    </a>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <form class="delete" action="manage/delete/<?= $this->e($product_manage->ma_sach) ?>" method="POST" style="display: inline;">
                                        <button type="button" class="btn btn-xs btn-danger button-delete" name="delete-product" data-bs-toggle="modal" data-bs-target="#delete-confirm" style="padding: 3px 9px;">
                                            <i alt="Delete" class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="delete-confirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xóa sản phẩm</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn muốn xóa sản phẩm <span class="product-info-delete fw-bold"></span> này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="delete">Xóa</button>
                    <button type="button" class="btn bg-secondary fw-bold text-white" data-bs-dismiss="modal">Không</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Metadata Modal (Add/Edit) -->
    <div class="modal fade" id="metadataModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="metadataForm" action="" method="POST">
                    <div class="modal-header bg-primary text-white shadow-sm">
                        <h5 class="modal-title fw-bold" id="metadataModalTitle">Quản lý Thuộc tính</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3" id="meta-id-group">
                            <label class="form-label fw-bold">Mã <span class="text-danger small">(Tự động sinh nếu để trống)</span></label>
                            <input type="text" class="form-control bg-light" name="id" id="meta-id" placeholder="Hệ thống tự sinh..." readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên hiển thị <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="meta-name" required placeholder="Nhập tên mới...">
                        </div>
                        <div id="publisher-fields" class="d-none">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" id="meta-phone">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ</label>
                                <textarea class="form-control" name="address" id="meta-address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Lưu thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('button.button-delete').on('click', function(e) {

                var form = $(this).closest('form');
                var ma_sach = $(this).closest('tr').find('input[name=id]').val();
                var ten_sach = $(this).closest('tr').find('td:eq(1)').text();

                if (ten_sach.length > 0) {
                    $('.product-info-delete').html(ten_sach + " (ID: " + ma_sach + ") ");
                }

                $('#delete-confirm').modal({
                    backdrop: 'static',
                    keyboard: false
                }).one('click', '#delete', function() {
                    form.submit();
                });
            });

            // Xử lý Metadata Modal
            $('.btn-add-metadata').on('click', function() {
                var type = $(this).data('type');
                resetMetaModal();
                $('#metadataModalTitle').text('Thêm mới ' + getMetaLabel(type));
                $('#metadataForm').attr('action', getAddAction(type));
                $('#meta-id').prop('readonly', false);
                togglePublisherFields(type === 'publisher');
                $('#metadataModal').modal('show');
            });

            // Xử lý xác nhận xóa chuyên nghiệp với SweetAlert2
            $(document).on('click', '.btn-delete-meta-trigger', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var name = form.data('name');

                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: 'Bạn có chắc chắn muốn xóa "' + name + '"? Hành động này không thể hoàn tác!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i> Có, xóa ngay!',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            function resetMetaModal() {
                $('#metadataForm')[0].reset();
                $('#publisher-fields').addClass('d-none');
                $('#meta-id-group').addClass('d-none'); // Ẩn cột mã khi thêm mới
            }

            function togglePublisherFields(show) {
                if (show) $('#publisher-fields').removeClass('d-none');
                else $('#publisher-fields').addClass('d-none');
            }

            $('.btn-edit-metadata').on('click', function() {
                var type = $(this).data('type');
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                resetMetaModal();
                $('#metadataModalTitle').text('Chỉnh sửa ' + getMetaLabel(type));
                $('#metadataForm').attr('action', getUpdateAction(type));
                $('#meta-id').val(id).prop('readonly', true);
                $('#meta-id-group').removeClass('d-none'); // Hiện mã khi sửa
                $('#meta-name').val(name);
                
                if (type === 'publisher') {
                    $('#meta-phone').val($(this).data('phone'));
                    $('#meta-address').val($(this).data('address'));
                    togglePublisherFields(true);
                } else {
                    togglePublisherFields(false);
                }
                
                $('#metadataModal').modal('show');
            });

            // Xử lý thông báo chuyên nghiệp
            <?php if ($success = session_get_once('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: '<?= $success ?>',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            <?php endif; ?>

            <?php if ($errors = session_get_once('errors')): ?>
                <?php if (isset($errors['metadata'])): ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể xóa',
                        text: '<?= $errors['metadata'] ?>',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Đã hiểu'
                    });
                <?php endif; ?>
            <?php endif; ?>

            function getMetaLabel(type) {
                switch(type) {
                    case 'type': return 'Loại Sách';
                    case 'author': return 'Tác Giả';
                    case 'publisher': return 'Nhà Xuất Bản';
                    default: return '';
                }
            }

            function getAddAction(type) {
                switch(type) {
                    case 'type': return '/bookstore/public/addType';
                    case 'author': return '/bookstore/public/addAuthor';
                    case 'publisher': return '/bookstore/public/addPublisher';
                    default: return '';
                }
            }

            function getUpdateAction(type) {
                switch(type) {
                    case 'type': return '/bookstore/public/updateType';
                    case 'author': return '/bookstore/public/updateAuthor';
                    case 'publisher': return '/bookstore/public/updatePublisher';
                    default: return '';
                }
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="/dashboard.js"></script>
</body>

</html>