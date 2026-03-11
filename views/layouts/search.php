<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page") ?>

<main class="container px-5">
    <?php
    echo '<h4 class="ps-5 my-5">Kết quả tìm kiếm từ khóa "' . $_POST['search'] . '": </h4>';
    echo '<div class="row mx-5">';
    foreach ($result as $rs) {
        echo '<div class="col-sm-6 col-md-6 col-lg-3 mb-3">';
        if ($rs->khuyen_mai > 0) echo '<div class="badge">' . $rs->khuyen_mai . '% </div>';
        echo
        '<div class="card">
                            <a href="detail?masp=' . $rs->ma_sach . '"><img class="card-img-top" src="/img/product/';
        echo $rs->hinh_anh . '" ></a>';
        echo '<div class="card-body">
                                <h6 class="card-title"><a style="color:#111111" href="detail?masp=' . $rs->ma_sach . '">' . $rs->ten_sach . '</a></h6>
                                <div class="row">
                                <div class="col ps-2">
                                <h5 class="fw-bold text-danger">' . number_format($rs->gia_sach * (100 - $rs->khuyen_mai) / 100, 0, '.', ',') . 'đ</h5></div>';
        if ($rs->khuyen_mai > 0) echo '<div class="col p-0"><span class="text-primary"><del>' . number_format($rs->gia_sach, 0, '.', ',') . 'đ</del></span></div>';
        echo '</div><form class="row"  action="addCart" method="POST"><input type="hidden" value="1"  name="so-luong">
                        <input type="hidden" name="masp" value="' . $rs->ma_sach . '">
                        <p class="col-8"> Đã bán: ' . $rs->sold . '</p>
                        <button class="col-4 btn btn-link pe-0"><i class="fas fa-cart-plus" style="color:#ec4276; font-size:24px;"></i></button></form></div></div></div>';
    }
    ?>

</main>
<?php $this->stop() ?>