<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page") ?>

<main class="container text-center pb-5">
    <div class="mt-5">
        <h4>LỊCH SỬ MUA HÀNG</h4>
    </div>
    <div class="row table-product mt-5">
        <table class="table text-center">
            <thead class="bg-info text-light">
                <tr>
                    <th scope="col">HÓA ĐƠN</th>
                    <th scope="col">TỔNG TIỀN</th>
                    <th scope="col">NGÀY MUA HÀNG</th>
                    <th scope="col">TRẠNG THÁI</th>
                    <th scope="col">THAO TÁC</th>
                    <th scope="col">XEM CHI TIẾT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bill_id = 0;
                foreach ($bills as $index => $hoadon) {
                    $hd = $hoadon['ma_hoa_don'];
                    if ($hd != $bill_id) {
                        echo '<tr class="align-middle">
                    <td class="col-1">' . $hoadon['ma_hoa_don'] . '</td>
                    <td class="col-2">' . number_format($hoadon['tong_tien'], 0, '.', ',')  . ' VNĐ</td>
                    <td class="col-2">' . $hoadon['ngay_lap'] . '</td>
                    <td class="col-3">';
                        if ($hoadon['trang_thai'] == "Canceled") {
                            echo '<p class="text-muted mt-3">Đã hủy</p>';
                        } else if ($hoadon['trang_thai'] == "processing") {
                            echo '<p class="text-info mt-3">Đang chuẩn bị hàng</p>';
                        } else if ($hoadon['trang_thai'] == "sending") {
                            echo '<p class="text-warning mt-3">Đang vận chuyển</p>';
                        } else if ($hoadon['trang_thai'] == "recieved") {
                            echo '<p class="text-success mt-3">Đơn hàng đã hoàn tất</p>';
                        }
                        echo '</td>
                    <td class="col">';
                    if ($hoadon['trang_thai'] == "processing") {
                        echo '
                    <form class="delete" action="cancelBill/' . $hoadon['ma_hoa_don'] . '" method="POST">
                        <button type="button" class="col btn btn-xs btn-danger button-cancel" name="delete-product" data-bs-toggle="modal" data-bs-target="#cancel-confirm">
                            Hủy Đơn
                    </button></form>';}
                    else if ($hoadon['trang_thai'] == "sending") {
                        echo '
                    <form action="received/' . $hoadon['ma_hoa_don'] . '" method="POST">
                        <button type="submit" class="col mt-1 btn btn-xs btn-success" name="recived">
                            Đã Nhận
                    </button>
                    </form>';} else echo '<p class="mt-3">Đã hoàn thành</p>';
                    echo '</td>
                    
                    <td class="col-2"><a href="detailBill?mhd=' . $hoadon->ma_hoa_don . '"><i class="fa fa-info-circle" style="color:#f2749b; font-size:24px;"></i></a></td>
                    </tr>';
                        $bill_id = $hd;
                    }
                }
                ?>
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="cancel-confirm" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hủy hóa đơn</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn là muốn hủy hóa đơn này không?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="cancel">Hủy</button>
                        <button type="button" class="btn bg-secondary fw-bold text-white" data-bs-dismiss="modal">Không</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="product_all" class="btn btn-primary">Tiếp tục mua</a>
</main>
<script>
    $(document).ready(function() {
        $('button.button-cancel').on('click', function(e) {

            var form = $(this).closest('form');

            $('#cancel-confirm').modal({
                backdrop: 'static',
                keyboard: false
            }).one('click', '#cancel', function() {
                form.submit();
            });
        });
    });
</script>
<?php $this->stop() ?>