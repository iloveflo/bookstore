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
                                Bài Viết
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
                    <a href="create" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Thêm Sản Phẩm</a>
                    <form class="text-right" role="search" action="manageProduct" method="POST">
                        <select class="form-select form-select-sm h-100" style="display: inline" name="sort-price" onchange="this.form.submit()">
                            <option value="1" <?= isset($old_selected['val']) ? ($old_selected['val'] == 1 ? 'selected' : '') : '' ?>>Mặc định</option>
                            <option value="2" <?= isset($old_selected['val']) ? ($old_selected['val'] == 2 ? 'selected' : '') : '' ?>>Giá Khuyến Mãi: Thấp đến Cao</option>
                            <option value="3" <?= isset($old_selected['val']) ? ($old_selected['val'] == 3 ? 'selected' : '') : '' ?>>Giá Khuyến Mãi: Cao đến Thấp</option>
                            <option value="4" <?= isset($old_selected['val']) ? ($old_selected['val'] == 4 ? 'selected' : '') : '' ?>>Sản phẩm bán chạy nhất</option>
                        </select>
                    </form>
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
    <!-- Modal -->
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
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="/dashboard.js"></script>
</body>

</html>