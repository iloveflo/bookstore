<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page") ?>
<?php $activePage = basename(rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')); ?>

<div class="container py-5"> 
    <div class="row">
        <div class="col-12 col-sm-3">
            <div class="card bg-light mb-3">
                <div class="card-header bg-purple text-white fw-bold"><i class="fa fa-list"></i> DANH MỤC </div>
                <ul class="list-group category_block">
                   <form action="product" method="GET">
    <li class="list-group-item"><button class="btn productBtn <?= (isset($_GET['sale']) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'sale')) ? 'active' : ''; ?>" name="sale">Khuyến Mãi HOT <?= (isset($_GET['sale']) || $_SESSION['menu'] == 'sale') ? '<span class="fs-3"> 🔥</span>' : ''; ?></button></li>
    <li class="list-group-item"><button class="btn productBtn <?= (isset($_GET['sgk']) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'sgk')) ? 'active' : ''; ?>" name="sgk">Sách Giáo Dục <?= (isset($_GET['sgk']) || $_SESSION['menu'] == 'sgk') ? '<span class="fs-3">🎓</span>' : ''; ?></button></li>
    <li class="list-group-item"><button class="btn productBtn <?= (isset($_GET['truyentranh']) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'truyentranh')) ? 'active' : ''; ?>" name="truyentranh">Truyện Tranh <?= (isset($_GET['truyentranh']) || $_SESSION['menu'] == 'truyentranh') ? '<span class="fs-4"> 🐳</span>' : ''; ?></button></li>
    <li class="list-group-item"><button class="btn productBtn <?= (isset($_GET['kynang']) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'kynang')) ? 'active' : ''; ?>" name="kynang">Kỹ Năng Sống <?= (isset($_GET['kynang']) || $_SESSION['menu'] == 'kynang') ? '<span class="fs-4"> 📒</span>' : ''; ?></button></li>
    <li class="list-group-item"><button class="btn productBtn <?= (isset($_GET['tieuthuyet']) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'tieuthuyet')) ? 'active' : ''; ?>" name="tieuthuyet">Tiểu Thuyết <?= (isset($_GET['tieuthuyet']) || $_SESSION['menu'] == 'tieuthuyet') ? '<span class="fs-4"> 🖊️</span>' : ''; ?></button></li>
    <li class="list-group-item"><a href="product_all" class="btn productBtn <?= ($activePage == 'product_all' || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'all')) ? 'active' : ''; ?>">Tất Cả Sản Phẩm <?= ($activePage == 'product_all' || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'all')) ? '<span class="fs-4"> 🌈</span>' : ''; ?></a></li>
</form>
                </ul>
            </div>
            
        </div>
        <div class="col">
        <div class="card bg-light mb-3">
                <div class="card-header bg-purple text-white fw-bold"><i class="fa fa-list"></i> SẮP XẾP THEO </div>
                <ul class="list-group category_block">
                    <form class="text-right" role="search" action="product" method="GET">
    <select class="form-select form-select-sm w-75 my-3 mx-2" style="display: inline" name="sort">
        <option selected>Giá</option>
        <option value="1">Giá: Thấp đến Cao</option>
        <option value="2">Giá: Cao đến Thấp</option>
        <option value="3">Sản phẩm bán chạy</option>
    </select>
    <?php 
    $s= 'all';
    // Đổi toàn bộ $_POST thành $_GET
    if ((isset($_GET['sgk'])) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'sgk')) {
            $s = 'sgk';
            } else if ((isset($_GET['truyentranh'])) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'truyentranh')) {
            $s = 'truyentranh';
            } else if ((isset($_GET['kynang'])) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'kynang')) {
            $s = 'kynang';
            } else if ((isset($_GET['tieuthuyet'])) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'tieuthuyet')) {
            $s = 'tieuthuyet';
            } else if ((isset($_GET['sale'])) || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'sale')) {
                $s = 'sale';
            } else if (($activePage == 'product_all') || (isset($_SESSION['menu']) && $_SESSION['menu'] == 'all')){
                $s = 'all';
            }?>
    <input type="hidden" name="select"  value="<?= $s ?>">
    <button type="submit" name="filter" class="btn btn-primary btn-sm my-2 mt-1">Lọc</button>
</form>
                </ul>
            </div>
            <div class="row" id='product'>
                <?php
                if (isset($products)) {
                    foreach ($products as $product) {
                        echo '<div class="col-sm-6 col-md-6 col-lg-3 mb-3 p-1">';
                        if ($product->khuyen_mai > 0) echo '<div class="badge">' . $product->khuyen_mai. '% </div>';
                        echo
                        '<div class="card">
                            <a href="/bookstore/public/detail?masp=' . $product->ma_sach . '"><img class="card-img-top" src="/img/product/';
                        echo $product->hinh_anh . '" ></a>';
                        echo '<div class="card-body">
                                <h6 class="card-title"><a style="color:#111111" href="/bookstore/public/detail?masp=' . $product->ma_sach . '">';   if (mb_strlen($product->ten_sach) > 31) { echo mb_substr($product->ten_sach, 0, 28) . " ...";} else echo $product->ten_sach;
                        echo'</a></h6>
                                <div class="row">
                                <div class="col ps-2">
                                <h5 class="fw-bold text-danger">' . number_format($product->gia_sach * (100 - $product->khuyen_mai) / 100, 0, '.', ',') . 'đ</h5></div>';
                        if ($product->khuyen_mai > 0) echo '<div class="col p-0"><span class="text-primary"><del>' . number_format($product->gia_sach, 0, '.', ',') . 'đ</del></span></div>';
                        echo '</div><form class="row"  action="addCart" method="POST"><input type="hidden" value="1"  name="so-luong">
                        <input type="hidden" name="masp" value="' .$product->ma_sach . '">
                        <p class="col-8"> Đã bán: ' .$product->sold . '</p>
                        <button class="col-4 btn btn-link pe-0"><i class="fas fa-cart-plus" style="color:#ec4276; font-size:24px;"></i></button></form></div></div></div>';
                    }
                } ?>

                <!-- <div class="card">
                        
                    </div> -->
            </div>
        </div>
    </div>

</div>

<?php $this->stop() ?>