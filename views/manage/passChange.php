<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>
<?php $this->start("page") ?>

<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
    <div class="mb-4 d-flex justify-content-center pt-3 pb-2 mb-3">
        <p class="fs-4 fw-bold">Thay Đổi Mật Khẩu</p>
    </div>
    <div class="inner-wrapper row">
        <div class="col-md-12 d-flex justify-content-center">

            <form name="frm" id="frm" action="updatePass?id=<?= $user->id ?>" method="post" class="col-md-6 col-md-offset-3 p-5 mb-5 bg-body border border-2" enctype="multipart/form-data">

                <!-- Old Pass -->
                <div class="form-group mb-3<?= isset($errors['password']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="password">Mật Khẩu Hiện Tại</label>
                    <input type="password" name="password" class="form-control border border-1 border-secondary" maxlen="255" id="password" placeholder="Nhập mật khẩu cũ" style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['password'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['password']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>

                <!-- New Pass -->
                <div class="form-group mb-3<?= isset($errors['new_password']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="new_password">Mật Khẩu Mới</label>
                    <input type="password" name="new_password" class="form-control border border-1 border-secondary" maxlen="255" id="new_password" placeholder="Nhập mật khẩu mới" style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['new_password'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['new_password']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>

                <!-- PassConfirm -->
                <div class="form-group mb-3<?= isset($errors['password_confirmation']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="password_confirmation">Xác Nhận Mật Khẩu Mới</label>
                    <input type="password" name="password_confirmation" class="form-control border border-1 border-secondary" maxlen="255" id="password_confirmation" placeholder="Nhập lại mật khẩu"  style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['password_confirmation'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['password_confirmation']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>

                <!-- Submit -->
                <input type="hidden" name="id" value="<?= $user->id?>">
                <button type="submit" name="submit" id="submit" class="btn btn-primary me-2">Đổi Mật Khẩu</button>
                <a href="userInfo?userId=<?= $user->id ?>" class="btn bg-secondary text-white fw-bold me-5">Hủy</a>
            </form>

        </div>
    </div>

</main>

<?php $this->stop() ?>