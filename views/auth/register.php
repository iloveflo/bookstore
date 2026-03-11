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
                        <li class="nav-link"><a href="<?= BASE_URL ?>/login">ĐĂNG NHẬP</a></li>
                        <li class="nav-link active"><a href="<?= BASE_URL ?>/register">ĐĂNG KÝ</a></li>
                    </ul>
                </div>
                <div class="panel-body bg-light p-5">

                    <form class="form-horizontal" role="form" method="POST" action="register">

                        <div class="mb-2 form-group<?= isset($errors['name']) ? ' has-error' : '' ?>">
                            <label for="name" class="col-md-4 control-label">Tên đăng nhập<code>*</code></label>
                            <div>
                                <input id="name" type="text" class="form-control" name="name" value="<?= isset($old['name']) ? $this->e($old['name']) : '' ?>" autofocus>

                                <?php if (isset($errors['name'])) : ?>
                                    <span class="help-block">
                                        <strong class="text-danger"><?= $this->e($errors['name']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-2 col-6 form-group<?= isset($errors['email']) ? ' has-error' : '' ?>">
                                <label for="email" class="col-md-4 control-label">E-Mail<code>*</code></label>
                                <div>
                                    <input id="email" type="email" class="form-control" name="email" value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>">

                                    <?php if (isset($errors['email'])) : ?>
                                        <span class="help-block">
                                            <strong class="text-danger"><?= $this->e($errors['email']) ?></strong>
                                        </span>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="mb-2 col-6 form-group<?= isset($errors['phone']) ? ' has-error' : '' ?>">
                                <label for="phone" class="col-md-6 control-label">Số điện thoại<code>*</code></label>
                                <div>
                                    <input id="phone" type="tel" class="form-control" name="phone" value="<?= isset($old['phone']) ? $this->e($old['phone']) : '' ?>">

                                    <?php if (isset($errors['phone'])) : ?>
                                        <span class="help-block">
                                            <strong class="text-danger"><?= $this->e($errors['phone']) ?></strong>
                                        </span>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 form-group<?= isset($errors['address']) ? ' has-error' : '' ?>">
                            <label for="address" class="col-md-4 control-label">Địa chỉ<code>*</code></label>
                            <div>
                                <input id="address" type="text" class="form-control" name="address">

                                <?php if (isset($errors['address'])) : ?>
                                    <span class="help-block">
                                        <strong class="text-danger"><?= $this->e($errors['address']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>

                        <div class="mb-2 form-group<?= isset($errors['password']) ? ' has-error' : '' ?>">
                            <label for="password" class="col-md-4 control-label">Mật khẩu<code>*</code></label>
                            <div>
                                <input id="password" type="password" class="form-control" name="password">

                                <?php if (isset($errors['password'])) : ?>
                                    <span class="help-block">
                                        <strong class="text-danger"><?= $this->e($errors['password']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>

                        <div class="mb-2 form-group<?= isset($errors['password_confirmation']) ? ' has-error' : '' ?>">
                            <label for="password-confirm" class="col-md-4 control-label">Nhập lại mật khẩu<code>*</code></label>
                            <div>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                <?php if (isset($errors['password_confirmation'])) : ?>
                                    <span class="help-block">
                                        <strong class="text-danger"><?= $this->e($errors['password_confirmation']) ?></strong>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>

                        <div class="form-group form-check m-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" required checked>
                            <label class="form-check-label">
                                Tôi đồng ý với các <a href="#">điều khoản và dịch vụ </a> của Bookworm Store.
                            </label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <button type="submit" class="w-100 btn btn-lg btn-primary">
                                    Tạo tài khoản ngay
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>