<?php $this->layout("/layouts/default", ["title" => APPNAME]) ?>
<?php $this->start("page") ?>

<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
    <div class="mb-4 d-flex justify-content-center pt-3 pb-2 mb-3">
        <p class="fs-4 fw-bold">Thông Tin Tài Khoản</p>
    </div>
    <div class="inner-wrapper row">
        <div class="col-md-12 d-flex justify-content-center">

            <form name="frm" id="frm" action="updateUser?id=<?= $user->id ?>" method="post" class="col-md-6 col-md-offset-3 p-5 mb-5 bg-body border border-2" enctype="multipart/form-data">

                <!-- Name -->
                <div class="form-group mb-3<?= isset($errors['name']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="name">Tên Tài Khoản</label>
                    <input type="text" name="name" class="form-control border border-1 border-secondary" maxlen="255" id="name" placeholder="Nhập tên" value="<?= isset($old_value['name']) ? $this->e($old_value['name']) : $this->e($user->name) ?>" style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['name'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['name']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>

                <!-- Email -->
                <div class="form-group mb-3<?= isset($errors['email']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="email">Email</label>
                    <input type="text" name="email" class="form-control border border-1 border-secondary" maxlen="255" id="email" placeholder="Nhập địa chỉ email" value="<?= isset($old_value['email']) ? $this->e($old_value['email']) : $this->e($user->email) ?>" style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['email'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['email']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>

                <!-- Phone -->
                <div class="form-group mb-3<?= isset($errors['phone']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="phone">Số Điện Thoại</label>
                    <input type="text" name="phone" class="form-control border border-1 border-secondary" id="phone" placeholder="Nhập số điện thoại" value="<?= isset($old_value['phone']) ? $this->e($old_value['phone']) : $this->e($user['phone']) ?>" style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['phone'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['phone']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>

                <!-- Address -->
                <div class="form-group mb-3<?= isset($errors['address']) ? ' has-error' : '' ?>">
                    <label class="fw-bold" for="address">Địa Chỉ</label>
                    <input type="text" name="address" class="form-control border border-1 border-secondary" id="address" placeholder="Nhập địa chỉ" value="<?= isset($old_value['address']) ? $this->e($old_value['address']) : $this->e($user['address']) ?>" style="background-color: #F3F6FF;" />

                    <?php if (isset($errors['address'])) : ?>
                        <span class="help-block text-danger">
                            <strong><?= $this->e($errors['address']) ?></strong>
                        </span>
                    <?php endif ?>
                </div>
                
                <!-- Submit -->
                <input type="hidden" name="id" value="<?= $user->id?>">
                <button type="submit" name="submit" id="submit" class="btn btn-primary me-2">Lưu</button>
                <a href="home" id="cancel-update" class="btn bg-secondary text-white fw-bold me-5">Hủy</a>
                <a href="passChange?id=<?= $user->id ?>" class="btn bg-info text-white fw-bold" style="margin-left: 165px;">Thay đổi mật khẩu</a>
            </form>

        </div>
    </div>

</main>

<?php $this->stop() ?>