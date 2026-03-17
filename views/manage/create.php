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

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a href="home" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 ">
            <h5 class="m-0 display-4 fs-5 text-secondary fw-bold"><span class="text-primary fs-5 fw-bold">BOOK</span>worm</h5>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="w-100 boder-bottom p-1">
            <h3 class="fs-4 px-2 text-white text-center text-uppercase pt-2">Quản lý sản phẩm</h3>
        </div>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="logout" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">Đăng xuất</a>
                <form id="logout-form" action="logout" method="POST" style="display: none;">
                </form>
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
                        <li class="nav-item">
                            <a class="nav-link active" href="manageProduct">
                                <i class="fa fa-book"></i>
                                Sản Phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manageBill">
                                <i class="fa fa-shopping-cart"></i>
                                Đơn Hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="users">
                                <i class="fas fa-user-friends"></i>
                                Người Dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manageArticles">
                                <i class="fas fa-newspaper"></i>
                                Bài Viết
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard">
                                <i class="fas fa-chart-line"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="mb-4 d-flex flex-row justify-content-between pt-3 pb-2 mb-3 border-bottom">
                    <p class="fs-5 fw-bold">Thêm Sản Phẩm</p>
                </div>
                <div class="inner-wrapper row">
                    <div class="col-md-12 d-flex justify-content-center">

                        <form name="frm" id="frm" action="createProduct" method="post" class="col-md-7 col-md-offset-3 p-5 bg-body border border-2 mb-5" enctype="multipart/form-data">

                            <!-- ID -->
                            <div class="form-group mb-3<?= isset($errors['ma_sach']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="id">Mã Sản Phẩm</label>
                                <input type="text" name="ma_sach" class="form-control border border-1 border-secondary" maxlen="255" id="id" placeholder="Nhập mã" value="<?= isset($ma_sp['ma_sach']) ? $this->e($ma_sp['ma_sach']) : '' ?>" style="background-color: #F3F6FF;" readonly />

                                <?php if (isset($errors['ma_sach'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['ma_sach']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Name -->
                            <div class="form-group mb-3<?= isset($errors['ten_sach']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="name">Tên Sản Phẩm</label>
                                <input type="text" name="ten_sach" class="form-control border border-1 border-secondary" maxlen="255" id="name" placeholder="Nhập tên" value="<?= isset($old['ten_sach']) ? $this->e($old['ten_sach']) : '' ?>" style="background-color: #F3F6FF;" />

                                <?php if (isset($errors['ten_sach'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['ten_sach']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Price -->
                            <div class="form-group mb-3<?= isset($errors['gia_sach']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="price">Giá Sản Phẩm (VNĐ)</label>
                                <input type="number" name="gia_sach" class="form-control border border-1 border-secondary" id="price" placeholder="Nhập giá" value="<?= isset($old['gia_sach']) ? $this->e($old['gia_sach']) : '' ?>" style="background-color: #F3F6FF;" />

                                <?php if (isset($errors['gia_sach'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['gia_sach']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Discount Percent -->
                            <div class="form-group mb-3<?= isset($errors['khuyen_mai']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="discount-percent">Phần Trăm Khuyến Mãi (%)</label>
                                <input type="number" name="khuyen_mai" class="form-control border border-1 border-secondary" id="discount-percent" placeholder="Nhập phần trăm khuyến mãi" value="<?= isset($old['khuyen_mai']) ? $this->e($old['khuyen_mai']) : '' ?>" style="background-color: #F3F6FF;" />

                                <?php if (isset($errors['khuyen_mai'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['khuyen_mai']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group mb-3<?= isset($errors['so_luong']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="quantity">Số Lượng Sản Phẩm</label>
                                <input type="number" name="so_luong" class="form-control border border-1 border-secondary" id="quantity" placeholder="Nhập số lượng" value="<?= isset($old['so_luong']) ? $this->e($old['so_luong']) : '' ?>" style="background-color: #F3F6FF;" />

                                <?php if (isset($errors['so_luong'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['so_luong']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Image -->
                            <div class="form-group mb-3<?= isset($errors['hinh_anh']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="image">Ảnh Sản Phẩm</label>
                                <input type="file" name="hinh_anh" class="form-control border border-1 border-secondary" id="image" placeholder="Nhập ảnh" style="background-color: #F3F6FF;" />

                                <?php if (isset($errors['hinh_anh'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['hinh_anh']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>
                            <div class="preview-image text-center" style="width: 110px"></div>

                            <div class="form-group mb-3<?= isset($errors['anh_1']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="image1">Ảnh Chi Tiết Sản Phẩm</label>
                                <div class="input-group">
                                    <input type="file" multiple name="anh[]" class="form-control border border-1 border-secondary" id="image1" placeholder="Nhập ảnh" style="background-color: #F3F6FF;" />
                                </div>

                                <?php if (isset($errors['anh_1'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['anh_1']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>
                            <div class="row">
                                <div class="preview-image1 col-6 p-0 mx-2 text-center" style="width: 110px"></div>
                                <div class="preview-image2 col-6 p-0 mx-2 text-center" style="width: 110px"></div>
                            </div>

                            <!-- Type -->
                            <div class="form-group mb-3<?= isset($errors['ma_loai_sach']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="type">Loại Sản Phẩm</label>
                                <select name="ma_loai_sach" class="form-select border border-1 border-secondary" id="type" style="background-color: #F3F6FF;">
                                    <?php foreach ($loai_sp as $type) : ?>
                                        <option value="<?= isset($type['ma_loai_sach']) ? $this->e($type['ma_loai_sach']) : '' ?>"><?= $this->e($type['ten_loai_sach']) ?></option>
                                    <?php endforeach ?>
                                </select>

                                <?php if (isset($errors['ma_loai_sach'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['ma_loai_sach']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Author -->
                            <div class="form-group mb-3<?= isset($errors['ma_tac_gia']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="type">Tác Giả</label>
                                <select name="ma_tac_gia" class="form-select border border-1 border-secondary" id="type" style="background-color: #F3F6FF;">
                                    <?php foreach ($tacgia as $t) : ?>
                                        <option value="<?= isset($t['ma_tac_gia']) ? $this->e($t['ma_tac_gia']) : '' ?>"><?= $this->e($t['ten_tac_gia']) ?></option>
                                    <?php endforeach ?>
                                </select>

                                <?php if (isset($errors['ma_tac_gia'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['ma_tac_gia']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- NXB -->
                            <div class="form-group mb-3<?= isset($errors['ma_nxb']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="type">Nhà Xuất Bản</label>
                                <select name="ma_nxb" class="form-select border border-1 border-secondary" id="type" style="background-color: #F3F6FF;">
                                    <?php foreach ($nxb as $n) : ?>
                                        <option value="<?= isset($n['ma_nxb']) ? $this->e($n['ma_nxb']) : '' ?>"><?= $this->e($n['ten_nxb']) ?></option>
                                    <?php endforeach ?>
                                </select>

                                <?php if (isset($errors['ma_nxb'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['ma_nxb']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>


                            <!-- Description -->
                            <div class="form-group mb-3<?= isset($errors['mo_ta']) ? ' has-error' : '' ?>">
                                <label class="fw-bold" for="description">Mô Tả Sản Phẩm </label>
                                <textarea name="mo_ta" id="description" class="form-control border border-1 border-secondary" rows="4" placeholder="Nhập mô tả (tối đa: 500 ký tự)" style="background-color: #F3F6FF;"><?= isset($old['mo_ta']) ? $this->e($old['mo_ta']) : '' ?></textarea>

                                <?php if (isset($errors['mo_ta'])) : ?>
                                    <span class="help-block text-danger">
                                        <strong><?= $this->e($errors['mo_ta']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Submit -->
                            <button type="submit" name="submit" id="submit" class="btn btn-primary me-2">Thêm Sản Phẩm</button>
                            <a href="manageProduct" id="cancel-create" class="btn bg-secondary text-white fw-bold">Hủy</a>
                        </form>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function() {
        // Set màu nền (giữ nguyên)
        $('body').css('background-color', '#F3F6FF');

        // Hàm đọc và hiển thị ảnh (Dùng chung cho cả 2 trường hợp)
        function showImage(fileInput, containerClass, imgId) {
            var container = $(containerClass);
            
            // 1. Reset khung ảnh trước khi xử lý
            container.empty(); 
            container.removeClass("mb-3 border border-2"); // Bỏ border nếu không có ảnh

            if (fileInput) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    // Chèn ảnh và thêm class trang trí
                    container.append("<img src='" + event.target.result + "' id='" + imgId + "' width='100px' class='img-fluid'>");
                    container.addClass("mb-3 border border-2");
                }

                reader.readAsDataURL(fileInput);
            }
        }

        // --- XỬ LÝ ẢNH CHÍNH ---
        $("#image").change(function() {
            // this.files[0] là file người dùng chọn
            showImage(this.files[0], '.preview-image', 'preview-image-main');
        });

        // --- XỬ LÝ ẢNH CHI TIẾT (Nhiều ảnh) ---
        $("#image1").change(function() {
            // Reset cả 2 khung ảnh chi tiết TRƯỚC khi kiểm tra
            // Đây là bước sửa lỗi quan trọng nhất
            $('.preview-image1').empty().removeClass("mb-3 border border-2");
            $('.preview-image2').empty().removeClass("mb-3 border border-2");

            // Xử lý ảnh 1
            if (this.files[0]) {
                showImage(this.files[0], '.preview-image1', 'preview-image-sub1');
            }

            // Xử lý ảnh 2
            if (this.files[1]) {
                showImage(this.files[1], '.preview-image2', 'preview-image-sub2');
            }
        });
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="/dashboard.js"></script>
</body>

</html>