<?php
use App\SessionGuard as Guard;
$currentUser = Guard::user();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Cập nhật bài viết">
    <title>Cập Nhật Bài Viết - Admin Bookworms Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="/img/favicon.jpg" rel="shortcut icon">
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
                            <a class="nav-link" aria-current="page" href="/bookstore/public/home">
                                <i class="fas fa-home"></i> Trang Chủ
                            </a>
                        </li>
                        <?php if ($currentUser->can('product.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/bookstore/public/manageProduct">
                                <i class="fa fa-book"></i> Sản Phẩm
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('bill.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/bookstore/public/manageBill">
                                <i class="fa fa-shopping-cart"></i> Đơn Hàng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('user.view_all') || $currentUser->can('user.view_customers')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/bookstore/public/users">
                                <i class="fas fa-user-friends"></i> Người Dùng
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('article.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="/bookstore/public/manageArticles">
                                <i class="fas fa-newspaper"></i> Bài Viết
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if ($currentUser->can('dashboard.view')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/bookstore/public/dashboard">
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
                    <p class="fs-5 fw-bold">Cập Nhật Bài Viết</p>
                </div>
                <div class="inner-wrapper row">
                    <div class="col-md-12 d-flex justify-content-center">

                        <form name="frm" id="frm" action="/bookstore/public/manageArticles/updateArticle/<?= $this->e($article->article_id) ?>" method="post" class="col-md-8 col-md-offset-2 p-5 mb-5 bg-body border border-2" enctype="multipart/form-data">

                            <div class="form-group mb-3<?= isset($errors['title']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="title">Tiêu Đề Bài Viết</label>
                                <input type="text" name="title" class="form-control border border-1 border-secondary" maxlen="255" id="title" placeholder="Nhập tiêu đề" value="<?= isset($old_value['title']) ? $this->e($old_value['title']) : $this->e($article->title) ?>" style="background-color: #F3F6FF;" />

                                <?php if (isset($errors['title'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['title']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <div class="form-group mb-3<?= isset($errors['status']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="status">Trạng Thái</label>
                                <?php 
                                    // Lấy trạng thái hiện tại (hoặc từ old_value nếu bị lỗi form)
                                    $currentStatus = isset($old_value['status']) ? $old_value['status'] : $article->status; 
                                ?>
                                <select name="status" class="form-select border border-1 border-secondary" id="status" style="background-color: #F3F6FF;">
                                    <option value="published" <?= $currentStatus == 'published' ? 'selected' : '' ?>>Xuất bản</option>
                                    <option value="draft" <?= $currentStatus == 'draft' ? 'selected' : '' ?>>Bản nháp</option>
                                    <option value="hidden" <?= $currentStatus == 'hidden' ? 'selected' : '' ?>>Đang ẩn</option>
                                </select>

                                <?php if (isset($errors['status'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['status']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <div class="form-group mb-3<?= isset($errors['summary']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="summary">Tóm Tắt Ngắn</label>
                                <textarea name="summary" id="summary" class="form-control border border-1 border-secondary" rows="3" placeholder="Nhập tóm tắt hiển thị ngoài danh sách" style="background-color: #F3F6FF;"><?= isset($old_value['summary']) ? $this->e($old_value['summary']) : $this->e($article->summary) ?></textarea>
                                
                                <?php if (isset($errors['summary'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['summary']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <div class="form-group mb-3<?= isset($errors['content']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="content">Nội Dung Chi Tiết</label>
                                <textarea name="content" id="content" class="form-control border border-1 border-secondary" rows="10" placeholder="Nhập nội dung bài viết..." style="background-color: #F3F6FF;"><?= isset($old_value['content']) ? $this->e($old_value['content']) : $this->e($article->content) ?></textarea>
                                
                                <?php if (isset($errors['content'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['content']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <div class="form-group mb-3<?= isset($errors['thumbnail']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="image">Ảnh Bìa</label>

                                <input type="file" name="thumbnail" class="form-control border border-1 border-secondary" id="image" style="background-color: #F3F6FF;" accept="image/*" />

                                <input type="hidden" name="thumbnail_cu" value="<?= $this->e($article->thumbnail) ?>">

                                <?php if (isset($errors['thumbnail'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['thumbnail']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <div class="preview-image mb-4 text-center border border-2" style="width: 160px; height: auto;">
                                <?php if($article->thumbnail): ?>
                                    <img src="/img/blog/<?= $this->e($article->thumbnail) ?>" id="preview-image-main" class="img-fluid" style="object-fit: cover;">
                                <?php endif; ?>
                            </div>

                            <button type="submit" name="submit" id="submit" class="btn btn-primary me-2 px-4">Lưu Bài Viết</button>
                            <a href="/bookstore/public/manageArticles" class="btn bg-secondary text-white fw-bold px-4">Hủy</a>
                        </form>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('body').css('background-color', '#F3F6FF');

            function showImage(fileInput, containerClass, imgId) {
                var container = $(containerClass);
                container.empty().removeClass("mb-4 border border-2");

                if (fileInput) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        container.append("<img src='" + event.target.result + "' id='" + imgId + "' class='img-fluid' style='object-fit: cover;'>");
                        container.addClass("mb-4 border border-2");
                    }
                    reader.readAsDataURL(fileInput);
                }
            }

            $("#image").change(function() {
                showImage(this.files[0], '.preview-image', 'preview-image-main');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>