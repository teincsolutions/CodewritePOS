<?= $this->extend('template/auth') ?>
<?= $this->section('content') ?>
<div class="account-content">
    <div class="login-wrapper">
        <div class="login-content">
            <div class="login-userset ">
                <div class="login-logo">
                    <img src="<?=base_url('assets/images/logo.png') ?>" alt="img">
                </div>
                <div class="login-userheading">
                    <h3>Reset Password</h3>
                </div>
                <div class="form-login">
                    <label>Password</label>
                    <div class="pass-group">
                        <input type="password" class="pass-input" placeholder="Enter your password">
                        <span class="fas toggle-password fa-eye-slash"></span>
                    </div>
                </div>
                <div class="form-login">
                    <label>Confirm Password</label>
                    <div class="pass-group">
                        <input type="password" class="pass-inputs" placeholder="Enter your password">
                        <span class="fas toggle-passwords fa-eye-slash"></span>
                    </div>
                </div>
                <div class="form-login">
                    <a class="btn  btn-login">Reset Password</a>
                </div>
            </div>
        </div>
        <div class="login-img">
        <img src="<?= base_url('assets/images/login-bg.jpg') ?>" alt="img">
        </div>
    </div>
</div>
<?= $this->endSection() ?>