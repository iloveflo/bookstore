<?php
$this->layout("/layouts/default", ["title" => APPNAME]);
$this->start("page") ?>

<main style="min-height: 60vh;" class="d-flex align-items-center py-5">
    <div class="container"> <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Quên mật khẩu</h3>
                        
                        <form action="/bookstore/public/forgot-password" method="POST">
                            <div class="mb-4">
                                <label class="form-label text-muted">Nhập địa chỉ email của bạn</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Gửi link xác nhận</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="/bookstore/public/login" class="text-decoration-none text-secondary">
                                <small>← Quay lại đăng nhập</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php $this->stop() ?>