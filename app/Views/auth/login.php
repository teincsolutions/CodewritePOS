<?= $this->extend('template/auth') ?>
<?= $this->section('title') ?>Login<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="account-content">
    <div class="login-wrapper">
        <div class="login-content">
            <div class="login-userset">
                <div class="login-logo logo-normal">
                    <img src="<?= base_url('assets/images/pos-logo.png') ?>" alt="img">
                </div>
                <a href="<?= site_url() ?>" class="login-logo logo-white">
                    <img src="<?= site_url('assets/images/pos-logo.png') ?>" alt>
                </a>
                <div class="login-userheading">
                    <h3>Sign In</h3>
                    <h4>Please login to your account</h4>
                </div>
                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                <?php elseif (session('errors') !== null) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php if (is_array(session('errors'))) : ?>
                            <?php foreach (session('errors') as $error) : ?>
                                <?= $error ?>
                                <br>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?= session('errors') ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                <?php endif ?>
                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-login">
                        <label>Email</label>
                        <div class="form-addons">
                            <input type="text" class="form-control" name="username" inputmode="text" autocomplete="email" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required />
                        </div>
                    </div>
                    <div class="form-login">
                        <label>Password</label>
                        <div class="pass-group">
                            <input type="password" class="form-control pass-input" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required />
                            <span class="fas toggle-password fa-eye-slash"></span>
                        </div>
                    </div>
                    <div class="form-login">
                        <div class="alreadyuser">
                            <h4><a href="<?= site_url('forgot-password') ?>" class="hover-a">Forgot Password?</a></h4>
                        </div>
                    </div>
                    <div class="form-login">
                        <button type="submit" class="btn btn-login" href="">Sign In</button>
                    </div>

            </div>
        </div>
        <div class="login-img">
            <img src="<?= base_url('assets/images/login-bg.jpg') ?>" alt="img">
        </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>