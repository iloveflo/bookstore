<?php
$this->layout("/layouts/default", ["title" => APPNAME]);
$this->start("page") ?>

<main style="min-height: 60vh;" class="d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Đặt lại mật khẩu</h3>
                        
                        <form action="/bookstore/public/reset-password" method="POST" onsubmit="return validatePassword()">
                            <input type="hidden" name="email" value="<?php echo $_GET['email'] ?? ''; ?>">
                            <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới (Tối thiểu 8 ký tự)</label>
                                <input type="password" name="password" id="password" class="form-control" required minlength="8" placeholder="Nhập mật khẩu mới">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nhập lại mật khẩu</label>
                                <input type="password" name="password_confirmation" id="confirm_password" class="form-control" required minlength="8" placeholder="Nhập lại mật khẩu bên trên">
                                <div id="msg" class="text-danger mt-1" style="font-size: 0.9rem;"></div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 btn-lg">Đổi mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Tự động kiểm tra khi người dùng gõ phím
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");

    function validatePassword(){
        if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Mật khẩu nhập lại không khớp!");
            return false; // Chặn submit
        } else {
            confirm_password.setCustomValidity('');
            return true; // Cho phép submit
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>

<?php $this->stop() ?>