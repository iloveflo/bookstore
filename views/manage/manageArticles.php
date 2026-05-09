<?php
use App\SessionGuard as Guard;
$currentUser = Guard::user();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Quản lý bài viết - Admin Bookworms Store">
    <title>Quản lý Bài Viết - Admin Bookworms Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="/img/favicon.jpg" rel="shortcut icon">
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
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

    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top admin-header flex-md-nowrap p-0 shadow-sm">
        <a href="home" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 ">
            <h5 class="m-0 display-4 fs-5 text-secondary fw-bold"><span class="text-primary fs-5 fw-bold">BOOK</span>worm</h5>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
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
                    <a class="nav-link btn-logout px-3" href="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
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
                            <a class="nav-link" href="home"><i class="fas fa-home"></i> Trang Chủ</a>
                        </li>
                        <?php if ($currentUser->can('product.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageProduct">
                                <i class="fa fa-book"></i>
                                Sản Phẩm
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('bill.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manageBill">
                                <i class="fa fa-shopping-cart"></i>
                                Đơn Hàng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('user.view_all') || $currentUser->can('user.view_customers')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="users">
                                <i class="fas fa-user-friends"></i>
                                Người Dùng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('article.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="manageArticles">
                                <i class="fas fa-newspaper"></i> Bài Viết
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
                <div class="mb-4 d-flex flex-row justify-content-between pt-3 pb-2 mb-3 border-bottom">
                    <a href="/bookstore/public/manageArticles/createArticle" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Thêm Bài Viết</a>

                    <form class="text-right" role="search" action="manageArticles" method="GET">
                        <select class="form-select form-select-sm h-100" style="display: inline" name="status" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="published" <?= (isset($_GET['status']) && $_GET['status'] == 'published') ? 'selected' : '' ?>>Đã xuất bản</option>
                            <option value="draft" <?= (isset($_GET['status']) && $_GET['status'] == 'draft') ? 'selected' : '' ?>>Bản nháp</option>
                            <option value="hidden" <?= (isset($_GET['status']) && $_GET['status'] == 'hidden') ? 'selected' : '' ?>>Đang ẩn</option>
                        </select>
                    </form>
                </div>

                <table id="all-articles" class="table table-bordered table-responsive mb-5" style="border-color: #cacaca!important;">
                    <thead class="bg-success text-light text-uppercase text-center align-middle">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Tiêu Đề</th>
                            <th style="width: 120px;">Ảnh Bìa</th>
                            <th>Người Đăng</th>
                            <th style="width: 140px;">Trạng Thái</th>
                            <th style="width: 150px;">Ngày Tạo</th>
                            <th style="width: 80px;">Sửa</th>
                            <th style="width: 80px;">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($articles) && count($articles) > 0) : ?>
                            <?php foreach ($articles as $article) : ?>
                                <tr style="font-size: 15px;">
                                    <td style="text-align: center; vertical-align: middle;"><?= $this->e($article->article_id) ?></td>

                                    <td style="vertical-align: middle;">
                                        <strong><?= $this->e($article->title) ?></strong>
                                    </td>

                                    <td style="text-align: center; vertical-align: middle;">
                                        <?php if ($article->thumbnail) : ?>
                                            <img src="/img/blog/<?= $this->e($article->thumbnail) ?>" style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">Không có ảnh</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="text-align: center; vertical-align: middle;">
                                        <?= $this->e($article->admin->name ?? 'Admin ẩn danh') ?>
                                    </td>

                                    <td style="padding: 24px;">
                                        <?php if ($article->status == 'published') : ?>
                                            <span class="badge bg-primary">Xuất bản</span>
                                        <?php elseif ($article->status == 'draft') : ?>
                                            <span class="badge bg-warning text-dark">Bản nháp</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">Đã ẩn</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="text-align: center; vertical-align: middle;">
                                        <?= date('d/m/Y H:i', strtotime($article->created_at)) ?>
                                    </td>

                                    <td style="text-align: center; vertical-align: middle;">
                                        <a href="/bookstore/public/manageArticles/editArticle/<?= $this->e($article->article_id) ?>" class="btn btn-xs btn-warning" style="padding: 3px 6px;">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>

                                    <td style="text-align: center; vertical-align: middle;">
                                        <form class="delete-form" action="/bookstore/public/manageArticles/deleteArticle/<?= $this->e($article->article_id) ?>" method="POST" style="display: inline;">
                                            <button type="button" class="btn btn-xs btn-danger button-delete"
                                                data-id="<?= $this->e($article->article_id) ?>"
                                                data-title="<?= $this->e($article->title) ?>"
                                                style="padding: 3px 9px;">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">Chưa có bài viết nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <?php
                    // Kiểm tra xem có đang lọc trạng thái không, nếu có thì tạo chuỗi nối vào URL
                    $statusParam = !empty($_GET['status']) ? '&status=' . $_GET['status'] : '';
                    ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">

                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= $statusParam ?>">Trước</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= $statusParam ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?><?= $statusParam ?>">Tiếp</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <div class="modal fade" id="delete-article-confirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Xác nhận xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa bài viết: <br>
                    <strong class="article-title-delete text-danger"></strong> ?<br>
                    <span class="text-muted" style="font-size: 0.9em;">(ID Bài viết: <span class="article-id-delete"></span>) - Hành động này không thể hoàn tác.</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">Xóa Bài Viết</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var formToSubmit;

            // Khi click nút xóa trên bảng
            $('.button-delete').on('click', function() {
                // Lấy form chứa nút vừa click
                formToSubmit = $(this).closest('.delete-form');

                // Lấy data từ attribute data-* của nút
                var articleId = $(this).data('id');
                var articleTitle = $(this).data('title');

                // Điền thông tin vào Modal
                $('.article-title-delete').text(articleTitle);
                $('.article-id-delete').text(articleId);

                // Hiển thị Modal
                $('#delete-article-confirm').modal('show');
            });

            // Khi click nút "Xóa Bài Viết" trong Modal
            $('#confirm-delete-btn').on('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>