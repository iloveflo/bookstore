<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page") ?>

<div class="page-header bg-body-login">
    <div class="row">
        <div class="col-md-6">
            <img class="pt-5 ms-5" style="margin-top: 9rem" src="/img/login.png" height="450px" alt="">
        </div>
        <div class="col-md-6 col-md-offset-2 p-5 mt-3 ">
            <div class="panel panel-default">
                <div class="pt-5">
                    <ul class="nav nav-tabs text-center">
                        <li class="nav-link"><a href="<?= BASE_URL ?>/login">ĐĂNG NHẬP</a></li>
                        <li class="nav-link active"><a href="<?= BASE_URL ?>/register">ĐĂNG KÝ</a></li>
                    </ul>
                </div>
                <div class="panel-body bg-light p-5">

                    <!-- Thêm ID 'register-form' và class 'needs-validation' để dễ dàng hook JS -->
                    <form id="register-form" class="form-horizontal needs-validation" role="form" method="POST" action="register" novalidate>

                        <div class="mb-2 form-group<?= isset($errors['name']) ? ' has-error' : '' ?>">
                            <label for="name" class="col-md-4 control-label">Tên đăng nhập<code>*</code></label>
                            <div>
                                <!-- Thêm pattern: Chỉ cho phép chữ, số, gạch dưới, dài 3-30 ký tự -->
                                <input id="name" type="text" class="form-control" name="name" 
                                       value="<?= isset($old['name']) ? $this->e($old['name']) : '' ?>" 
                                       pattern="^[a-zA-Z0-9_]{3,30}$" 
                                       title="Tên đăng nhập từ 3-30 ký tự, không chứa ký tự đặc biệt" 
                                       required autofocus>

                                <?php if (isset($errors['name'])) : ?>
                                    <span class="help-block"><strong class="text-danger"><?= $this->e($errors['name']) ?></strong></span>
                                <?php endif ?>
                                <div class="invalid-feedback">Vui lòng nhập tên đăng nhập hợp lệ (3-30 ký tự, không ký tự đặc biệt).</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-2 col-6 form-group<?= isset($errors['email']) ? ' has-error' : '' ?>">
                                <label for="email" class="col-md-4 control-label">E-Mail<code>*</code></label>
                                <div>
                                    <!-- HTML5 type="email" đã tự động validate định dạng email cơ bản -->
                                    <input id="email" type="email" class="form-control" name="email" 
                                           value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>" 
                                           required>

                                    <?php if (isset($errors['email'])) : ?>
                                        <span class="help-block"><strong class="text-danger"><?= $this->e($errors['email']) ?></strong></span>
                                    <?php endif ?>
                                    <div class="invalid-feedback">Vui lòng nhập đúng định dạng email (VD: abc@domain.com).</div>
                                </div>
                            </div>

                            <div class="mb-2 col-6 form-group<?= isset($errors['phone']) ? ' has-error' : '' ?>">
                                <label for="phone" class="col-md-6 control-label">Số điện thoại<code>*</code></label>
                                <div>
                                    <!-- Thêm pattern: Yêu cầu chính xác 10 chữ số, bắt đầu bằng số 0 -->
                                    <input id="phone" type="tel" class="form-control" name="phone" 
                                           value="<?= isset($old['phone']) ? $this->e($old['phone']) : '' ?>" 
                                           pattern="^0[0-9]{9}$" 
                                           title="Số điện thoại phải bắt đầu bằng số 0 và có chính xác 10 chữ số" 
                                           required>

                                    <?php if (isset($errors['phone'])) : ?>
                                        <span class="help-block"><strong class="text-danger"><?= $this->e($errors['phone']) ?></strong></span>
                                    <?php endif ?>
                                    <div class="invalid-feedback">Số điện thoại phải có đúng 10 số và bắt đầu bằng số 0.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 form-group<?= isset($errors['address']) ? ' has-error' : '' ?>">
                            <label for="address" class="col-md-4 control-label">Địa chỉ<code>*</code></label>
                            <div>
                                <!-- Thêm minlength để tránh việc nhập rác (VD: nhập mỗi chữ "A") -->
                                <input id="address" type="text" class="form-control" name="address" minlength="5" required>

                                <?php if (isset($errors['address'])) : ?>
                                    <span class="help-block"><strong class="text-danger"><?= $this->e($errors['address']) ?></strong></span>
                                <?php endif ?>
                                <div class="invalid-feedback">Vui lòng nhập địa chỉ cụ thể (tối thiểu 5 ký tự).</div>
                            </div>
                        </div>

                        <div class="mb-2 form-group<?= isset($errors['password']) ? ' has-error' : '' ?>">
                            <label for="password" class="col-md-4 control-label">Mật khẩu<code>*</code></label>
                            <div>
                                <!-- Thêm minlength cho bảo mật cơ bản -->
                                <input id="password" type="password" class="form-control" name="password" minlength="6" required>

                                <?php if (isset($errors['password'])) : ?>
                                    <span class="help-block"><strong class="text-danger"><?= $this->e($errors['password']) ?></strong></span>
                                <?php endif ?>
                                <div class="invalid-feedback">Mật khẩu phải chứa ít nhất 6 ký tự.</div>
                            </div>
                        </div>

                        <div class="mb-2 form-group<?= isset($errors['password_confirmation']) ? ' has-error' : '' ?>">
                            <label for="password-confirm" class="col-md-4 control-label">Nhập lại mật khẩu<code>*</code></label>
                            <div>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                <?php if (isset($errors['password_confirmation'])) : ?>
                                    <span class="help-block"><strong class="text-danger"><?= $this->e($errors['password_confirmation']) ?></strong></span>
                                <?php endif ?>
                                <div class="invalid-feedback">Mật khẩu nhập lại không khớp.</div>
                            </div>
                        </div>

                        <div class="form-group form-check m-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" required checked>
                            <label class="form-check-label">
                                Tôi đồng ý với các <a href="#">điều khoản và dịch vụ </a> của Bookworm Store.
                            </label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <button type="submit" class="w-100 btn btn-lg btn-primary">
                                    Tạo tài khoản ngay
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Khối Script xử lý Validation Client-side -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('register-form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password-confirm');

    // Hàm kiểm tra mật khẩu khớp nhau
    function validatePasswordMatch() {
        if (password.value !== confirmPassword.value) {
            // Sử dụng setCustomValidity API của HTML5 để đánh dấu input này là invalid
            confirmPassword.setCustomValidity("Mật khẩu không khớp.");
        } else {
            // Đặt chuỗi rỗng để đánh dấu là valid
            confirmPassword.setCustomValidity(""); 
        }
    }

    // Lắng nghe sự kiện khi người dùng gõ phím
    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);

    // Chặn submit nếu form không hợp lệ (Tích hợp Bootstrap validation style)
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault(); // Ngăn trình duyệt gửi form
            event.stopPropagation();
        }
        
        // Thêm class 'was-validated' để Bootstrap hiển thị các dòng .invalid-feedback
        form.classList.add('was-validated'); 
    }, false);
});
</script>

<?php $this->stop() ?>