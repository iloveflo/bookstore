<?php
$this->layout("/layouts/default", ["title" => APPNAME]);
$this->start("page") ?>
<main>
    <div class="page-header bg-body-login">
        <div class="row align-items-md-stretch">
            <div class="col-md-6">
                <img class="pt-5 ms-5" style="margin-top: 7rem" src="/img/login.png" height="450px" alt="">
            </div>
            <div class="col-md-6 p-5 mt-3 ">
                <div class="pt-5">
                    <ul class="nav nav-tabs text-center">
                        <li class="nav-link active"><a href="login">ĐĂNG NHẬP</a></li>
                        <li class="nav-link"><a href="register">ĐĂNG KÝ</a></li>
                    </ul>
                </div>
                <div class="bg-light p-5">
                    <form id="singinForm" method="post" action="login">

                        <?php
                        if (isset($error_wrong)) {
                            foreach ($error_wrong as $err_wrong) {
                                echo "<p class='text-danger fw-bold'>" . $err_wrong . "</p>";
                            }
                        }
                        ?>

                        <div class="mb-2 form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                            <label class="form-label">Email<code>*</code></label>
                            <input for="email" type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>" autofocus>
                            <?php if (isset($errors['email'])) : ?>
                                <span class="help-block">
                                    <p class="text-danger fw-bold"><?= $this->e($errors['email']) ?></p>
                                </span>
                            <?php endif ?>
                        </div>

                        <div class="form-group mb-2 <?= isset($errors['password']) ? 'has-error' : '' ?>">
                            <label for="pwd" class="form-label">Mật khẩu<code>*</code></label>
                            <div class="input-group ">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                <input class="btn btn-outline-custom input-group-text" type="button" id="toggleBtn" onclick="togglePassword()" value="🐵">
                            </div>
                            <?php if (isset($errors['password'])) : ?>
                                <span class="help-block">
                                    <p class="text-danger fw-bold"><?= $this->e($errors['password']) ?></p>
                                </span>
                            <?php endif ?>
                        </div>

                        <div class="row">
                            <div class="checkbox mb-3 col-6">
                                <label>
                                    <input type="checkbox" name="remember_me" value="1"> Ghi nhớ tôi
                                </label>
                            </div>
                            <div class="col-6">
                                <a href="/bookstore/public/forgot-password" class="text-decoration-none float-end" data-target="#pwdModal" data-toggle="modal">Quên mật khẩu</a>
                            </div>
                        </div>

                        <p class="text-center" style="font-size: 18px;">Hoặc đăng nhập với</p>
                        <div class="row justify-content-center">
                            <a href="/bookstore/public/auth/google" class="btn btn-light mb-4 col-3 me-2">
                                <img src="/img/Googleicon.png" alt="Google Login" style="height: 40px;">
                            </a>

                            <a href="/bookstore/public/auth/facebook" class="btn btn-light mb-4 col-3 ms-2">
                                <img src="/img/facebookicon.png" alt="Facebook Login" style="height: 40px;">
                            </a>
                        </div>

                        <button class="w-100 btn btn-lg btn-primary" type="submit">Đăng nhập</button>
                    </form>
                </div>
            </div>
        </div>
</main>

<script type="text/javascript">
    function togglePassword() {
        var password = document.getElementById('password');
        var toggleBtn = document.getElementById('toggleBtn');
        if (password.type == "password") {
            password.type = "text";
            toggleBtn.value = "🙈";
        } else {
            password.type = "password";
            toggleBtn.value = "🐵";
        }
    }
</script>

<?php $this->stop() ?>