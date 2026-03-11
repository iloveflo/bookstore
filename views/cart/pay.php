<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page") ?>

<main class="container text-center pb-5">
    <div class=" py-4">
        <img src="/img/done.png" width="200px" alt="">
    </div>
    <div class="py-2">
        <h4 class="fw-bold my-2">Đăng ký mua hàng thành công</h4>
        <p>Cảm ơn quý khách đã gửi đăng ký mua hàng tại Bookworms Store</p>
    </div>
    <div style="padding-left: 240px; padding-right: 240px">
        <p>Trong trường hợp khẩn cấp vui lòng gọi đến Hotline: <b class="text-danger">0375217905</b> Chân thành cảm ơn!</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hoadon">Xem Hóa Đơn</button>

    <div class="modal modal-lg fade" id="hoadon" tabindex="-1" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>CHI TIẾT HÓA ĐƠN  </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row table-product mt-1">
                        <table class="table text-center">
                            <thead class="bg-info text-light">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">SẢN PHẨM</th>
                                    <th scope="col"></th>
                                    <th scope="col">SỐ LƯỢNG</th>
                                    <th scope="col">THÀNH TIỀN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($bill)) {
                                    foreach ($bill as $index => $item) {

                                        echo '<tr class="align-middle">
                        <th scope="row">' . $index + 1 . '</th>
                        <td class="col-2"> <a href="/detail?masp=' . $item->ma_sach . '""><img src="/img/product/' . $item->hinh_anh . '" width="80%" ></a></td>
                        <td><p class="text-dark text-start">' . $item['ten_sach'] . '</p> <p class="text-dark text-start fw-bold">' . number_format($item->gia_khuyen_mai, 0, '.', ',') . 'đ</p></td>
                        <td>' . $item['so_luong_sp'] . '</td>
                        <td>' . number_format($item['gia_khuyen_mai'] * $item['so_luong_sp'], 0, '.', ',')  . 'đ</td>      
                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    if (isset($billdetail)) {
                        $k = 0;
                        foreach ($billdetail as $item) {
                            while ($item['ma_hoa_don'] != $k) {
                                echo '<div class="row bg-info bg-opacity-10 rounded"><div class="col text-start mt-4">
            <p><b>Trạng thái đơn hàng: </b>' ; if ($item['trang_thai'] == "Canceled")  {echo 'Đã hủy';} 
            else if ($item['trang_thai'] == "processing")  {echo 'Đang chuẩn bị hàng';}
            else if ($item['trang_thai'] == "sending")  {echo 'Đang vận chuyển';} 
            else if ($item['trang_thai'] == "recieved")  {echo 'Đơn hàng đã hoàn tất';} echo '</p>
            <p><b>Trạng thái thanh toán: </b>' . $item['trang_thai_thanh_toan'] . '</p>
            <p><b>Ngày đặt hàng: </b>' . $item['ngay_lap'] . '</p>
            </div>
            <div class="col text-end mt-4">
            <p><b>Địa chỉ nhận hàng:</b></p>
            <p>' . $item['ten_khach_hang'] . ' ' . $item['sdt'] . '</p>
            <p>' . $item['dia_chi'] . '</p></div>
            </div>';
                                $k = $item['ma_hoa_don'];
                            }
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>


</main>
<?php $this->stop() ?>