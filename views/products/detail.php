<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>
<?php $this->start("page") ?>

<main>
    <div class="container mb-5">
        <div class="row pt-5 ">
            <div class="modal fade" id="img-1" tabindex="-1" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img width="100%" src="/img/product/<?php echo $product->hinh_anh ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="img-2" tabindex="-1" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img width="100%" src="/img/product/<?php echo $product->anh_1 ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="img-3" tabindex="-1" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img width="100%" src="/img/product/<?php echo $product->anh_2 ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="row"><button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#img-1"><img width="80%" src="/img/product/<?php echo $product->hinh_anh ?>" /></button></div>
                <div class="row"><button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#img-2"><img width="80%" src="/img/product/<?php echo $product->anh_1 ?>" /></button></div>
                <div class="row"><button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#img-3"><img width="80%" src="/img/product/<?php echo $product->anh_2 ?>" /></button></div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-4 text-center">
                <img style= "height:300px" src="/img/product/<?php echo $product->hinh_anh ?>" />
            </div>
            <div class="col-sm-10 col-md-12 col-lg-7 pb-2">
                <h3 class="pb-3 mb-3"><?= $product->ten_sach ?></h3>
                <div class="row">
                    <p class="col-6">Nhà Xuất Bản: <b><?= $product->ten_nxb ?></b></p>
                    <p class="col-6">Tác Giả: <b><?= $product->ten_tac_gia ?></b></p>
                </div>
                <p><b>Đã bán: </b><?= $product->sold ?></p>
                <div class="row">
                    <h2 class="col-3 text-danger"><?= number_format($product->gia_khuyen_mai, 0, '.', ',') ?>đ</h2>
                    <?php if ($product->khuyen_mai>0){
                        echo '<del class="col-3 mt-2 text-muted position-relative" style="left: -30px;">' . number_format($product->gia_sach, 0, '.', ','). 'đ</del>
                        <div class="badge-detail text-center col-3">-' .$product->khuyen_mai . '%</div>';
                    }
                    ?>
                </div>

                <p><?= $product->mo_ta ?></p>
                <hr>

                <p class="fw-bold col-3">SỐ LƯỢNG</p>


                <form action="addCart" method="POST">
                    <div class="row ps-3"><input class="col-2 text-center" type="number" value="1" min="1" max="<?= $product->so_luong ?>" name="so-luong">
                        <p class="col-3 mb-0 text-muted">Kho: <?= $product->so_luong ?></p>
                    </div>
                    <input class=" btn btn-danger col-4 mt-3" type="submit" name="addCart" value="THÊM VÀO GIỎ HÀNG"></input>
                    <input type="hidden" name="masp" value="<?= $product->ma_sach ?>">
                </form>

            </div>
        </div>
    </div>
</main>
<?php $this->stop() ?>