<?php $this->layout("/layouts/default", ["title" => APPNAME])?>

<?php $this->start("page") ?>
<div class="container-fluid p-0 page-header position-relative text-center" style="z-index: 8;">
    <img src="/img/store.png" width="1200px" alt="">
</div>

<!-- <div class="container-fluid p-0"> -->

<form action="product" method="GET">
    <div class="container mx-auto pt-5 row">
        <div class="col text-center">
            <button name="sale" class="btn btn-link p-0 m-0"><img src="/img/khuyenmai.png" width="60px" alt=""></button>
            <p>Khuyến mãi</p>
        </div>
        <div class="col text-center">
            <button name="all" class="btn btn-link p-0 m-0"><img src="/img/newproduct.png" width="60px" alt=""></button>
            <p>Sản Phẩm Mới</p>
        </div>
        <div class="col text-center">
            <button name="sgk" class="btn btn-link p-0 m-0"><img src="/img/sgk.png" width="60px" alt=""></button>
            <p>Sách Giáo Dục</p>
        </div>
        <div class="col text-center">
            <button name="truyentranh" class="btn btn-link p-0 m-0"><img src="/img/comic.png" width="60px" alt=""></button>
            <p>Truyện Tranh</p>
        </div>
        <div class="col text-center">
            <button name="kynang" class="btn btn-link p-0 m-0"><img src="/img/kynang.png" width="60px" alt=""></button>
            <p>Kỹ Năng Sống</p>
        </div>
        <div class="col text-center">
            <button name="tieuthuyet" class="btn btn-link p-0 m-0"><img src="/img/vanhoc.png" width="60px" alt=""></button>
            <p>Tiểu Thuyết</p>
        </div>
    </div>
</form>
<!-- <div class="container-fluid">
    <img src="/img/banner.jpg" class="w-100" alt="">
 </div> -->
<section class="overflow-hidden">

    <div class="container">
        <div class="row h-100">
            <div class="py-3 mx-auto my-3 text-center bg-info bg-opacity-10">
                <h3 class="fs-3 fs-lg-5 m-0">🔖 SẢN PHẨM NỔI BẬT 🔖</h3>
            </div>
            <div class="text-lg-right">
                <a href="product_all">
                    <h5 class="text-secondary">Xem tất cả ></h5>
                </a>
            </div>
            <div class="col-12">
                <div class="carousel slide" id="carouselPopularItems" data-bs-touch="false" data-bs-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="10000">
                            <div class="row gx-3 h-100 align-items-center">
                                <?php
                                foreach ($product as $index => $item) {
                                    if ($index <= 4) {
                                        echo '    
                                <div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
                                        if ($item->khuyen_mai > 0) {
                                            echo '<div class="badge">' . $item->khuyen_mai . '% </div>';
                                        };
                                        echo '<div class="card card-span h-100 rounded-3"><a href="/bookstore/public/detail?masp=' . $item->ma_sach . '"><img class="img-fluid rounded-3 h-100" src="/img/product/' . $item->hinh_anh . '" alt="..." /></a>
                                        <div class="card-body">
                                            <p class="text-1000 mb-1"> ' . $item->ten_sach . '</p>
                                            <div class=""><span class="text-warning me-2"><i class="fa fa-tag fa-lg"></i></span><span class="text-primary"><del> ' . number_format($item->gia_sach, 0, '.', ',') . 'đ</del></span></div>
                                            <span class="text-1000 fs-5 fw-bold">💵' . number_format($item->gia_khuyen_mai, 0, '.', ',') . 'đ</span>
                                        </div>
                                    </div>
                                    <form class="row"  action="addCart" method="POST"><input type="hidden" value="1"  name="so-luong">
                                    <input type="hidden" name="masp" value="' . $item->ma_sach . '">
                                    <div class="d-grid gap-2 mt-1"><button class="btn btn-lg btn-danger" href="#!">👜 Chọn mua</button></div></form>
                                </div>';
                                    }
                                } ?>
                            </div>
                        </div>
                        <div class="carousel-item" data-bs-interval="5000">
                            <div class="row gx-3 h-100 align-items-center">
                                <?php
                                foreach ($product as $index => $item) {
                                    if ($index >= 5 && $index <= 9) {
                                        echo '    
                                    <div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
                                        if ($item->khuyen_mai > 0) {
                                            echo '<div class="badge">' . $item->khuyen_mai . '% </div>';
                                        };
                                        echo '<div class="card card-span h-100 rounded-3"><a href="/detail?masp=' . $item->ma_sach . '"><img class="img-fluid rounded-3 h-100" src="/img/product/' . $item->hinh_anh . '" alt="..." /></a>
                                            <div class="card-body">
                                                <p class="text-1000 mb-1"> ' . $item->ten_sach . '</p>
                                                <div class=""><span class="text-warning me-2"><i class="fa fa-tag fa-lg"></i></span><span class="text-primary"><del> ' . number_format($item->gia_sach, 0, '.', ',') . 'đ</del></span></div>
                                                <span class="text-1000 fs-5 fw-bold">💵' . number_format($item->gia_khuyen_mai, 0, '.', ',') . 'đ</span>
                                            </div>
                                        </div>
                                        <form class="row"  action="addCart" method="POST"><input type="hidden" value="1"  name="so-luong">
                                    <input type="hidden" name="masp" value="' . $item->ma_sach . '">
                                    <div class="d-grid gap-2 mt-1"><button class="btn btn-lg btn-danger" href="#!">👜 Chọn mua</button></div></form>
                                </div>';
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev-custom carousel-icon-custom" type="button" data-bs-target="#carouselPopularItems" data-bs-slide="prev">
                        <span class="carousel-control-prev-custom-icon hover-top-shadow" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next-custom carousel-icon-custom" type="button" data-bs-target="#carouselPopularItems" data-bs-slide="next">
                        <span class="carousel-control-next-custom-icon hover-top-shadow" aria-hidden="true"></span>
                        <span class="visually-hidden">Next </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- Carousel Start -->
<div id="header-carousel" class="carousel slide mx-auto position-relative w-100 pt-5" style="z-index: 7;" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="0" class="active" aria-label="Slide 1" aria-current="true"></button>
        <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
        <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="2" aria-label="Slide 3" class=""></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item carousel-item-next carousel-item-start" data-bs-interval="3000">
            <img src="/img/banner1.jpg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item" data-bs-interval="3000">
            <img src="/img/banner2.jpg" class="d-block  w-100" alt="...">
        </div>
        <div class="carousel-item active carousel-item-start" data-bs-interval="3000">
            <img src="/img/banner3.jpg" class="d-block w-100" alt="...">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<!-- Carousel End -->

<!-- Featured Products Start -->
<section class="overflow-hidden">

    <div class="container">
        <div class="row h-100">
            <div class="py-3 mx-auto my-3 text-center bg-info bg-opacity-10">
                <h3 class="fs-3 fs-lg-5 m-0">🔖 SẢN PHẨM KHUYẾN MÃI 🔖</h3>
            </div>
            <div class="text-lg-right">
                <a href="product_all">
                    <h5 class="text-secondary">Xem tất cả ></h5>
                </a>
            </div>
            <div class="col-12">
                <div class="carousel slide" id="carouselPop" data-bs-touch="false" data-bs-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="10000">
                            <div class="row gx-3 h-100 align-items-center">
                                <?php
                                foreach ($product_sale as $index => $item) {
                                    if ($index <= 4) {
                                        echo '    
                                <div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
                                        if ($item->khuyen_mai > 0) {
                                            echo '<div class="badge">' . $item->khuyen_mai . '% </div>';
                                        };
                                        echo '<div class="card card-span h-100 rounded-3"><a href="/bookstore/public/detail?masp=' . $item->ma_sach . '"><img class="img-fluid rounded-3 h-100" src="/img/product/' . $item->hinh_anh . '" alt="..." /></a>
                                        <div class="card-body">
                                            <p class="text-1000 mb-1"> ' . $item->ten_sach . '</p>
                                            <div class=""><span class="text-warning me-2"><i class="fa fa-tag fa-lg"></i></span><span class="text-primary"><del> ' . number_format($item->gia_sach, 0, '.', ',') . 'đ</del></span></div>
                                            <span class="text-1000 fs-5 fw-bold">💵' . number_format($item->gia_khuyen_mai, 0, '.', ',') . 'đ</span>
                                        </div>
                                    </div>
                                    <form class="row"  action="addCart" method="POST"><input type="hidden" value="1"  name="so-luong">
                                    <input type="hidden" name="masp" value="' . $item->ma_sach . '">
                                    <div class="d-grid gap-2 mt-1"><button class="btn btn-lg btn-danger" href="#!">👜 Chọn mua</button></div></form>
                                </div>';
                                    }
                                } ?>
                            </div>
                        </div>
                        <div class="carousel-item" data-bs-interval="5000">
                            <div class="row gx-3 h-100 align-items-center">
                                <?php
                                foreach ($product_sale as $index => $item) {
                                    if ($index >= 5 && $index <= 9) {
                                        echo '    
                                    <div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
                                        if ($item->khuyen_mai > 0) {
                                            echo '<div class="badge">' . $item->khuyen_mai . '% </div>';
                                        };
                                        echo '<div class="card card-span h-100 rounded-3"><a href="/detail?masp=' . $item->ma_sach . '"><img class="img-fluid rounded-3 h-100" src="/img/product/' . $item->hinh_anh . '" alt="..." /></a>
                                            <div class="card-body">
                                                <p class="text-1000 mb-1"> ' . $item->ten_sach . '</p>
                                                <div class=""><span class="text-warning me-2"><i class="fa fa-tag fa-lg"></i></span><span class="text-primary"><del> ' . number_format($item->gia_sach, 0, '.', ',') . 'đ</del></span></div>
                                                <span class="text-1000 fs-5 fw-bold">💵' . number_format($item->gia_khuyen_mai, 0, '.', ',') . 'đ</span>
                                            </div>
                                        </div>
                                        <form class="row"  action="addCart" method="POST"><input type="hidden" value="1"  name="so-luong">
                                    <input type="hidden" name="masp" value="' . $item->ma_sach . '">
                                    <div class="d-grid gap-2 mt-1"><button class="btn btn-lg btn-danger" href="#!">👜 Chọn mua</button></div></form>
                                </div>';
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev-custom carousel-icon-custom" type="button" data-bs-target="#carouselPop" data-bs-slide="prev">
                        <span class="carousel-control-prev-custom-icon hover-top-shadow" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next-custom carousel-icon-custom" type="button" data-bs-target="#carouselPop" data-bs-slide="next">
                        <span class="carousel-control-next-custom-icon hover-top-shadow" aria-hidden="true"></span>
                        <span class="visually-hidden">Next </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- Featured Products End -->
<div class="container-fluid p-0 m-0 text-center">
    <img class="w-100" src="/img/bg-footer.png" alt="">
</div>
<!-- </main> -->
<!-- </div> -->
<?php $this->stop() ?>